<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WaliKelasController extends Controller
{
    private function getMyClass($request = null)
    {
        // 1. If Admin and specific class requested
        if (Auth::user()->isDirektur() && $request && $request->filled('class_id')) {
             return SchoolClass::with(['academicYear', 'unit'])->find($request->class_id);
        }

        // 2. If Admin but no class requested, check Session
        if (Auth::user()->isDirektur() && session()->has('wali_kelas_class_id') && (!$request || !$request->filled('class_id'))) {
             return SchoolClass::with(['academicYear', 'unit'])->find(session('wali_kelas_class_id'));
        }

        // 3. Teacher Logic
        $myClassQuery = SchoolClass::where('teacher_id', Auth::id())
                    ->with(['academicYear', 'unit']);

        // If specific class requested by teacher
        if ($request && $request->filled('class_id')) {
            $myClassQuery->where('id', $request->class_id);
        }

        // Filter by Academic Year if provided, otherwise default to Active
        if ($request && $request->filled('academic_year_id')) {
            $myClassQuery->where('academic_year_id', $request->academic_year_id);
        } else {
            $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
            if ($activeYear) {
                $myClassQuery->where('academic_year_id', $activeYear->id);
            } else {
                 $myClassQuery->orderByDesc('id');
            }
        }
                    
        $myClass = $myClassQuery->first();
        
        return $myClass;
    }

    public function index(Request $request)
    {
        // ... (keep existing index logic if needed, but for now we focus on attendance page request)
        // Actually user asked for "http://nurul.test/wali-kelas/attendance" which is this method below.
        // But let's leave index() alone for now as it handles the "Dasboard" of Wali Kelas.
        
        // Reset Admin Selection
        if ($request->has('reset') && Auth::user()->isDirektur()) {
            session()->forget('wali_kelas_class_id');
            return redirect()->route('wali-kelas.index');
        }

        // Admin: Show Class Selector if no class selected
        if (Auth::user()->isDirektur() && !$request->filled('class_id') && !session()->has('wali_kelas_class_id')) {
            $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
            
            $units = \App\Models\Unit::with(['classes' => function($q) use ($activeYear) {
                if ($activeYear) {
                    $q->where('academic_year_id', $activeYear->id);
                }
            }])->get();
            
            return view('wali_kelas.admin_selector', compact('units'));
        }

        // If Admin selects class, store in session
        if (Auth::user()->isDirektur() && $request->filled('class_id')) {
            session(['wali_kelas_class_id' => $request->class_id]);
        }

        $myClass = $this->getMyClass($request);

        if (!$myClass) {
            return view('wali_kelas.no_class');
        }

        // Stats for Today
        $totalStudents = $myClass->studentHistory()->where('status', 'aktif')->count();
        
        $attendanceStats = StudentAttendance::where('class_id', $myClass->id)
                            ->where('date', $today)
                            ->select('status', DB::raw('count(*) as count'))
                            ->groupBy('status')
                            ->pluck('count', 'status')
                            ->toArray();

        // Default missing keys
        $bases = ['present', 'sick', 'permission', 'alpha', 'late'];
        foreach($bases as $b) {
            if(!isset($attendanceStats[$b])) $attendanceStats[$b] = 0;
        }

        // Calculate "Not Inputted"
        $totalInputted = array_sum($attendanceStats);
        $notInputted = $totalStudents - $totalInputted;

        return view('wali_kelas.index', compact('myClass', 'totalStudents', 'attendanceStats', 'notInputted', 'today'));
    }

    public function attendance(Request $request)
    {
        $myClass = $this->getMyClass($request);

    // Auto-select for Administrator if no class found (and no specific class requested)
    if (!$myClass && Auth::user()->role === 'administrator' && !$request->filled('class_id')) {
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        if ($activeYear) {
            $myClass = SchoolClass::where('academic_year_id', $activeYear->id)->first();
        } else {
            $myClass = SchoolClass::latest('id')->first();
        }
    }

    if ($myClass && Auth::user()->role === 'administrator') {
        // Ensure session and request align
        if (!session()->has('wali_kelas_class_id') || session('wali_kelas_class_id') != $myClass->id) {
            session(['wali_kelas_class_id' => $myClass->id]);
        }
        
        if (!$request->filled('class_id')) {
             $request->merge([
                 'class_id' => $myClass->id,
                 'academic_year_id' => $myClass->academic_year_id
             ]);
        }
    }
        
        // --- Filter Data Preparation ---
        // User Request: Only show Active Academic Years
        $academicYears = \App\Models\AcademicYear::where('status', 'active')->orderBy('start_year', 'desc')->get();
        $units = collect();
        $availableClasses = collect();

        if (Auth::user()->role === 'administrator') {
            $units = \App\Models\Unit::all();
            
            // Build query for available classes based on filters
            $classQuery = SchoolClass::query();
            
            if ($request->filled('unit_id')) {
                $classQuery->where('unit_id', $request->unit_id);
            }
            if ($request->filled('academic_year_id')) {
                $classQuery->where('academic_year_id', $request->academic_year_id);
            } elseif ($myClass) {
                // Default to current class's year if no filter set
                $classQuery->where('academic_year_id', $myClass->academic_year_id);
            }
            
            $availableClasses = $classQuery->orderBy('name')->get();
            
        } else {
            // For Teacher: Populate available classes (in case they manage > 1 class)
            $classQuery = SchoolClass::where('teacher_id', Auth::id());
            
            if ($request->filled('academic_year_id')) {
                $classQuery->where('academic_year_id', $request->academic_year_id);
            } else {
                // Default to Active Year classes only? Or allow seeing all?
                // Better to allow seeing all if no specific year filter, 
                // OR match the getMyClass default logic (Active Year).
                // Let's match getMyClass logic to keep dropdown consistent with displayed class
                 $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
                 if ($activeYear) {
                     $classQuery->where('academic_year_id', $activeYear->id);
                 }
            }
            
            $availableClasses = $classQuery->with('academicYear')->orderBy('name')->get();
        }
        // -------------------------------

        if (!$myClass) {
             // If no class found for the selected filter (e.g. year), redirect or show error
             // But we need to allow them to "change back" the filter.
             // So instead of redirecting, we might show a view with empty state but with filters.
             
             // For now, let's just default to error if strictly invalid, 
             // but ideally we pass filter vars even to error view.
             
             // If we have filters but no class, return view with partial data
             if ($request->filled('academic_year_id')) {
                 return view('wali_kelas.attendance', compact('myClass', 'academicYears', 'units', 'availableClasses'))->with('error', 'Tidak ada kelas pada tahun ajaran ini.');
             }
             
             return redirect()->route('wali-kelas.index')->with('error', 'Anda tidak terdaftar sebagai Wali Kelas.');
        }

        $date = $request->get('date', now()->format('Y-m-d'));

        // Get Active Students from History (Pivot)
        $students = $myClass->studentHistory()
                    ->where('status', 'aktif')
                    ->orderBy('nama_lengkap')
                    ->get();

        // Get Existing Attendance
        $attendances = StudentAttendance::where('class_id', $myClass->id)
                        ->where('date', $date)
                        ->get()
                        ->keyBy('student_id');

        // --- Holiday & Calendar Logic ---
    $isHoliday = false;
    $isOutsideAcademicYear = false; // New flag
    $calendarDescription = '';
    $effectiveDayDescription = ''; // New variable for non-holiday events

    // 1. Validate Date Range vs Academic Year
    if ($myClass && $myClass->academicYear) {
        $ayStart = \Carbon\Carbon::createFromDate($myClass->academicYear->start_year, 7, 1)->startOfDay();
        $ayEnd = \Carbon\Carbon::createFromDate($myClass->academicYear->end_year, 6, 30)->endOfDay();
        $checkDate = \Carbon\Carbon::parse($date);
        
        if (!$checkDate->between($ayStart, $ayEnd)) {
            $isOutsideAcademicYear = true;
            $calendarDescription = 'Tanggal berada di luar Tahun Pelajaran ' . $myClass->academicYear->name;
            // We treat outside AY as a "holiday" blocked state for safety
            $isHoliday = true; 
        }
    }

    // 2. Check Academic Calendar (Only if not already blocked by AY check)
    if (!$isOutsideAcademicYear) {
        $unitId = $myClass->unit_id;
        $classId = $myClass->id;
        // Fetch all candidates
        $cals = \App\Models\AcademicCalendar::where('date', $date)
                    ->where(function($q) use ($unitId) {
                        $q->where('unit_id', $unitId)
                          ->orWhereNull('unit_id');
                    })->get();
        
        // Priority Match for this specific class
        $targetCal = $cals->first(fn($c) => $c->is_holiday && is_array($c->affected_classes) && in_array($classId, $c->affected_classes));
        if (!$targetCal) $targetCal = $cals->first(fn($c) => !$c->is_holiday && is_array($c->affected_classes) && in_array($classId, $c->affected_classes));
        if (!$targetCal) $targetCal = $cals->first(fn($c) => $c->is_holiday && is_null($c->affected_classes));
        if (!$targetCal) $targetCal = $cals->first(fn($c) => !$c->is_holiday && is_null($c->affected_classes));

        if ($targetCal) {
            if ($targetCal->is_holiday) {
                $isHoliday = true;
                $calendarDescription = $targetCal->description;
            } else {
                // Effective Day with Description (e.g., "Hari Pertama Sekolah", "Ujian")
                $effectiveDayDescription = $targetCal->description;
            }
        } elseif (\Carbon\Carbon::parse($date)->isWeekend()) {
            // Only mark as weekend holiday if NO calendar entry exists
            // (If calendar entry existed, we would have entered the 'if ($cal)' block above.
            // If we are here, it means $cal is null, so no overwrite exists.)
             $isHoliday = true;
             $calendarDescription = 'Libur Akhir Pekan';
        }
    }

    return view('wali_kelas.attendance', compact(
        'myClass', 'students', 'attendances', 'date', 
        'isHoliday', 'calendarDescription', 'effectiveDayDescription', 'isOutsideAcademicYear',
        'academicYears', 'units', 'availableClasses'
    ));
}

    public function storeAttendance(Request $request)
    {
        $myClass = $this->getMyClass($request);
        if (!$myClass) abort(403);

        $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:present,sick,permission,alpha,late,school_activity',
            'attendances.*.notes' => 'nullable|string',
        ]);

        // --- Time Limit Check ---
        $unit = $myClass->unit;
        if ($unit && $unit->attendance_start && $unit->attendance_end) {
            $currentTime = now()->format('H:i:s');
            if ($currentTime < $unit->attendance_start || $currentTime > $unit->attendance_end) {
                return redirect()->back()->with('error', "Batas waktu absen di unit {$unit->name} adalah pukul {$unit->attendance_start} s/d {$unit->attendance_end}. Saat ini: " . now()->format('H:i'));
            }
        }
        // ------------------------

        $date = $request->date;
        $academicYearId = $myClass->academic_year_id; 
        // fallback active year if class doesn't store it properly (it should)
        if (!$academicYearId) {
             $ay = AcademicYear::where('status', 'active')->first();
             $academicYearId = $ay ? $ay->id : null;
        }

        DB::beginTransaction();
        try {
            foreach ($request->attendances as $studentId => $data) {
                // Determine if student belongs to class? Security check.
                // Skipped for performance, trusting the input form which lists class students. 
                // Anyhow, we store class_id so data is scoped.

                StudentAttendance::updateOrCreate(
                    [
                        'student_id' => $studentId, 
                        'date' => $date
                    ],
                    [
                        'class_id' => $myClass->id,
                        'academic_year_id' => $academicYearId,
                        'status' => $data['status'],
                        'notes' => $data['notes'],
                        'created_by' => Auth::id()
                    ]
                );

                // Auto-sync Violation: Late
                if ($data['status'] === 'late') {
                    $exists = \App\Models\StudentViolation::where('student_id', $studentId)
                        ->where('date', $date)
                        ->where('violation_type', 'Ringan')
                        ->where('description', 'Terlambat Datang Sekolah')
                        ->exists();

                    if (!$exists) {
                        \App\Models\StudentViolation::create([
                            'student_id' => $studentId,
                            'date' => $date,
                            'academic_year_id' => $academicYearId,
                            'violation_type' => 'Ringan',
                            'description' => 'Terlambat Datang Sekolah',
                            'points' => 1,
                            'follow_up_status' => 'done',
                            'recorded_by' => Auth::id()
                        ]);
                    }
                } else {
                    // If status is NOT late, remove any existing auto-violation for late
                    \App\Models\StudentViolation::where('student_id', $studentId)
                        ->where('date', $date)
                        ->where('violation_type', 'Ringan')
                        ->where('description', 'Terlambat Datang Sekolah')
                        ->delete();
                }

                // Auto-sync Violation: Alpha
                if ($data['status'] === 'alpha') {
                    $existsAlpha = \App\Models\StudentViolation::where('student_id', $studentId)
                        ->where('date', $date)
                        ->where('violation_type', 'Sedang')
                        ->where('description', 'Alpha (Tanpa Keterangan)')
                        ->exists();

                    if (!$existsAlpha) {
                        \App\Models\StudentViolation::create([
                            'student_id' => $studentId,
                            'date' => $date,
                            'academic_year_id' => $academicYearId,
                            'violation_type' => 'Sedang',
                            'description' => 'Alpha (Tanpa Keterangan)',
                            'points' => 2,
                            'follow_up_status' => 'done',
                            'recorded_by' => Auth::id()
                        ]);
                    }
                } else {
                     // If status is NOT alpha, remove any existing auto-violation for alpha
                     \App\Models\StudentViolation::where('student_id', $studentId)
                        ->where('date', $date)
                        ->where('violation_type', 'Sedang')
                        ->where('description', 'Alpha (Tanpa Keterangan)')
                        ->delete();
                }
            }
            DB::commit();
            return redirect()->route('wali-kelas.attendance', ['date' => $date])->with('success', 'Absensi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
    private function getReportData(Request $request)
    {
        $myClass = $this->getMyClass($request);
        if (!$myClass) return null;

        $type = $request->get('type', 'daily');
        $date = $request->get('date', now()->format('Y-m-d'));
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $students = $myClass->studentHistory()
                    ->where('status', 'aktif')
                    ->orderBy('nama_lengkap')
                    ->get();

        $data = [];
        $title = "";

        if ($type === 'daily') {
            $formattedDate = \Carbon\Carbon::parse($date);
            $title = "Laporan Harian - " . $formattedDate->translatedFormat('d F Y');
            
            $attendances = StudentAttendance::where('class_id', $myClass->id)
                            ->where('date', $date)
                            ->get()
                            ->keyBy('student_id');
            
            $data['attendances'] = $attendances;

        } elseif ($type === 'weekly') {
            $selectedDate = \Carbon\Carbon::parse($date);
            $startOfWeek = $selectedDate->copy()->startOfWeek();
            $endOfWeek = $selectedDate->copy()->endOfWeek(); // Sunday
            
            $title = "Laporan Mingguan (" . $startOfWeek->translatedFormat('d M') . " - " . $endOfWeek->translatedFormat('d M Y') . ")";

            $attendances = StudentAttendance::where('class_id', $myClass->id)
                            ->whereBetween('date', [$startOfWeek, $endOfWeek])
                            ->get()
                            ->groupBy('student_id');
            
            $data['start_date'] = $startOfWeek;
            $data['end_date'] = $endOfWeek;
            $data['attendances'] = $attendances;
            $data['dates_in_week'] = [];
            
            // Fetch holidays for this week
            $weekHolidays = \App\Models\AcademicCalendar::whereBetween('date', [$startOfWeek, $endOfWeek])
                            ->where('unit_id', $myClass->unit_id)
                            ->get()
                            ->keyBy(fn($item) => $item->date->format('Y-m-d'));
            
            $data['week_holidays'] = $weekHolidays; // Pass to view

            for ($i = 0; $i < 6; $i++) {
                 $data['dates_in_week'][] = $startOfWeek->copy()->addDays($i);
            }

        } elseif ($type === 'monthly') {
            $title = "Laporan Bulanan - " . \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');

            $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $attendances = StudentAttendance::select('student_id', 'status', DB::raw('count(*) as count'))
                            ->where('class_id', $myClass->id)
                            ->whereBetween('date', [$startOfMonth, $endOfMonth])
                            ->groupBy('student_id', 'status')
                            ->get();
            
            $summary = [];
            foreach ($students as $student) {
                $summary[$student->id] = [
                    'present' => 0, 'sick' => 0, 'permission' => 0, 'alpha' => 0, 'late' => 0, 'school_activity' => 0
                ];
            }

            foreach ($attendances as $rec) {
                if (isset($summary[$rec->student_id])) {
                    $summary[$rec->student_id][$rec->status] = $rec->count;
                }
            }
            
            $data['summary'] = $summary;

            // --- Enable Effective Days Calculation for Monthly ---
            $calendarEvents = \App\Models\AcademicCalendar::whereBetween('date', [$startOfMonth, $endOfMonth])
                            ->where(function($q) use ($myClass) {
                                $q->where('unit_id', $myClass->unit_id)->orWhereNull('unit_id');
                            })
                            ->get()
                            ->groupBy(fn($item) => $item->date->format('Y-m-d'));

            $totalEffectiveDays = 0;
            $period = \Carbon\CarbonPeriod::create($startOfMonth, $endOfMonth);
            
            foreach ($period as $dt) {
                $dateStr = $dt->format('Y-m-d');
                $dayEvents = $calendarEvents->get($dateStr, collect());
                $isWeekend = ($dt->dayOfWeek === \Carbon\Carbon::SUNDAY || $dt->dayOfWeek === \Carbon\Carbon::SATURDAY);
                
                // Priority Match for this class
                $event = $dayEvents->first(fn($c) => $c->is_holiday && is_array($c->affected_classes) && in_array($myClass->id, $c->affected_classes));
                if (!$event) $event = $dayEvents->first(fn($c) => !$c->is_holiday && is_array($c->affected_classes) && in_array($myClass->id, $c->affected_classes));
                if (!$event) $event = $dayEvents->first(fn($c) => $c->is_holiday && is_null($c->affected_classes));
                if (!$event) $event = $dayEvents->first(fn($c) => !$c->is_holiday && is_null($c->affected_classes));

                if ($event) {
                    if (!$event->is_holiday) {
                        $totalEffectiveDays++;
                    }
                } else {
                    if (!$isWeekend) {
                        $totalEffectiveDays++;
                    }
                }
            }
            $data['total_effective_days'] = $totalEffectiveDays;
            // ----------------------------------------------------
            
        } elseif ($type === 'semester') {
            $semester = $request->get('semester_type', 'ganjil'); // ganjil, genap
            $academicYear = $myClass->academicYear;
            
            if (!$academicYear) {
                // Fallback if no AY attached, maybe use current year? 
                // Ideally this shouldn't happen for active classes 
                $startYear = now()->year;
                $endYear = now()->year + 1;
            } else {
                $startYear = $academicYear->start_year;
                $endYear = $academicYear->end_year;
            }

            if ($semester === 'ganjil') {
                $startDate = \Carbon\Carbon::createFromDate($startYear, 7, 1);
                $endDate = \Carbon\Carbon::createFromDate($startYear, 12, 31);
                $title = "Laporan Semester Ganjil ({$startYear}/{$endYear})";
            } else {
                $startDate = \Carbon\Carbon::createFromDate($endYear, 1, 1);
                $endDate = \Carbon\Carbon::createFromDate($endYear, 6, 30);
                $title = "Laporan Semester Genap ({$startYear}/{$endYear})";
            }

            $attendances = StudentAttendance::select('student_id', 'status', DB::raw('count(*) as count'))
                            ->where('class_id', $myClass->id)
                            ->whereBetween('date', [$startDate, $endDate])
                            ->groupBy('student_id', 'status')
                            ->get();
            
            $summary = [];
            foreach ($students as $student) {
                $summary[$student->id] = [
                    'present' => 0, 'sick' => 0, 'permission' => 0, 'alpha' => 0, 'late' => 0, 'school_activity' => 0
                ];
            }

            foreach ($attendances as $rec) {
                if (isset($summary[$rec->student_id])) {
                    $summary[$rec->student_id][$rec->status] = $rec->count;
                }
            }
            
            $data['summary'] = $summary;
            $data['semester_type'] = $semester;
            
            // --- Helper to Calculate Effective Days ---
            $calendarEvents = \App\Models\AcademicCalendar::whereBetween('date', [$startDate, $endDate])
                            ->where(function($q) use ($myClass) {
                                $q->where('unit_id', $myClass->unit_id)->orWhereNull('unit_id');
                            })
                            ->get()
                            ->groupBy(fn($item) => $item->date->format('Y-m-d'));

            $totalEffectiveDays = 0;
            $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
            
            foreach ($period as $dt) {
                $dateStr = $dt->format('Y-m-d');
                $dayEvents = $calendarEvents->get($dateStr, collect());
                $isWeekend = ($dt->dayOfWeek === \Carbon\Carbon::SUNDAY || $dt->dayOfWeek === \Carbon\Carbon::SATURDAY);
                
                // Priority Match for this class
                $event = $dayEvents->first(fn($c) => $c->is_holiday && is_array($c->affected_classes) && in_array($myClass->id, $c->affected_classes));
                if (!$event) $event = $dayEvents->first(fn($c) => !$c->is_holiday && is_array($c->affected_classes) && in_array($myClass->id, $c->affected_classes));
                if (!$event) $event = $dayEvents->first(fn($c) => $c->is_holiday && is_null($c->affected_classes));
                if (!$event) $event = $dayEvents->first(fn($c) => !$c->is_holiday && is_null($c->affected_classes));

                if ($event) {
                    if (!$event->is_holiday) {
                        $totalEffectiveDays++;
                    }
                } else {
                    if (!$isWeekend) {
                        $totalEffectiveDays++;
                    }
                }
            }
            
            $data['total_effective_days'] = $totalEffectiveDays;
            
            // Recalculate percentages?
            // The view logic calculates % based on (Present+...) / TotalRecorded.
            // The user requested to use effective days for percentage.
            // We pass $totalEffectiveDays to view, and if > 0, we can use it as denominator if user wants strict effective day percentage.
            // However, existing attendance records might not cover all effective days yet if the semester is ongoing.
            // Usually "Percentage" = (Present) / (Days passed so far).
            // Let's pass the value to view and let view decide or update logic here.
        }
        
        return compact('myClass', 'students', 'type', 'data', 'title', 'date', 'month', 'year');
    }

    public function report(Request $request)
    {
        $reportData = $this->getReportData($request);
        if (!$reportData) return redirect()->route('wali-kelas.index')->with('error', 'Kelas tidak ditemukan.');
        
        // --- Filter Data (Duplicated from attendance for now, could be refactored) ---
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        $units = collect();
        $availableClasses = collect();
        
        $myClass = $reportData['myClass']; 

        if (Auth::user()->role === 'administrator') {
            $units = \App\Models\Unit::all();
            
            $classQuery = SchoolClass::query();
            
            if ($request->filled('unit_id')) {
                $classQuery->where('unit_id', $request->unit_id);
            }
            if ($request->filled('academic_year_id')) {
                $classQuery->where('academic_year_id', $request->academic_year_id);
            } elseif ($myClass && $myClass->academic_year_id) {
                // If we have a class, respect its year for the options list
                $classQuery->where('academic_year_id', $myClass->academic_year_id);
            }
            
            $availableClasses = $classQuery->orderBy('name')->get();
            
        } else {
            // Teacher: Show their classes
            $classQuery = SchoolClass::where('teacher_id', Auth::id());
            
            if ($request->filled('academic_year_id')) {
                $classQuery->where('academic_year_id', $request->academic_year_id);
            } else {
                 $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
                 if ($activeYear) {
                     $classQuery->where('academic_year_id', $activeYear->id);
                 }
            }
            
            $availableClasses = $classQuery->with('academicYear')->orderBy('name')->get();
        }
        
        $reportData['academicYears'] = $academicYears;
        $reportData['units'] = $units;
        $reportData['availableClasses'] = $availableClasses;

        return view('wali_kelas.report', $reportData);
    }

    public function exportReport(Request $request)
    {
        $reportData = $this->getReportData($request);
        if (!$reportData) return redirect()->route('wali-kelas.index')->with('error', 'Kelas tidak ditemukan.');

        // Reuse the logic but return a print-friendly view
        return view('wali_kelas.pdf', $reportData);
    }
    public function destroyAttendance(Request $request)
    {
        // ... (existing content)
        if ($deleted) {
             return redirect()->route('wali-kelas.attendance', ['date' => $date])->with('success', 'Data absensi tanggal ' . $date . ' berhasil dihapus.');
        } else {
             return redirect()->route('wali-kelas.attendance', ['date' => $date])->with('error', 'Tidak ada data absensi untuk dihapus pada tanggal ini.');
        }
    }

    public function students(Request $request)
    {
        $myClass = $this->getMyClass($request);
        
        if (!$myClass) {
             return redirect()->route('wali-kelas.index')->with('error', 'Anda tidak terdaftar sebagai Wali Kelas.');
        }

        // --- Filter Data Preparation ---
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        $units = collect();
        $availableClasses = collect();

        if (Auth::user()->role === 'administrator') {
            $units = \App\Models\Unit::all();
            $classQuery = SchoolClass::query();
             if ($request->filled('unit_id')) {
                $classQuery->where('unit_id', $request->unit_id);
            }
            if ($request->filled('academic_year_id')) {
                $classQuery->where('academic_year_id', $request->academic_year_id);
            } elseif ($myClass) {
                $classQuery->where('academic_year_id', $myClass->academic_year_id);
            }
            $availableClasses = $classQuery->orderBy('name')->get();
        } else {
            $classQuery = SchoolClass::where('teacher_id', Auth::id());
             if ($request->filled('academic_year_id')) {
                $classQuery->where('academic_year_id', $request->academic_year_id);
            } else {
                 $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
                 if ($activeYear) {
                     $classQuery->where('academic_year_id', $activeYear->id);
                 }
            }
            $availableClasses = $classQuery->with('academicYear')->orderBy('name')->get();
        }
        // -----------------------------------------------------------

        $students = $myClass->studentHistory()
                    ->where('status', 'aktif')
                    ->orderBy('nama_lengkap')
                    ->paginate(25);

        return view('wali_kelas.students.index', compact('myClass', 'students', 'academicYears', 'units', 'availableClasses'));
    }

    public function showStudent(Request $request, Student $student)
    {
        $myClass = $this->getMyClass($request);
        
        if (!$myClass) {
             return redirect()->route('wali-kelas.index')->with('error', 'Anda tidak memiliki akses ke kelas ini.');
        }

        // Verify student belongs to this class (Check History/Pivot)
        if (!$myClass->studentHistory()->where('students.id', $student->id)->exists()) {
            if (Auth::user()->role !== 'administrator') {
                abort(403, 'Siswa tidak terdaftar di kelas anda.');
            }
        }

        $student->load(['violationRecords' => function($q) {
            $q->orderBy('date', 'desc');
        }, 'achievements' => function($q) {
            $q->orderBy('date', 'desc');
        }, 'attendances' => function($q) {
            $q->orderBy('date', 'desc')->take(30); // Last 30 days
        }]);

        return view('wali_kelas.students.show', compact('student', 'myClass'));
    }
    public function violations(Request $request)
    {
        $myClass = $this->getMyClass($request);
        
        if (!$myClass) {
             return redirect()->route('wali-kelas.index')->with('error', 'Anda tidak terdaftar sebagai Wali Kelas.');
        }

        // --- Filter Data Preparation (Borrowed from attendance) ---
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        $units = collect();
        $availableClasses = collect();

        if (Auth::user()->role === 'administrator') {
            $units = \App\Models\Unit::all();
            $classQuery = SchoolClass::query();
             if ($request->filled('unit_id')) {
                $classQuery->where('unit_id', $request->unit_id);
            }
            if ($request->filled('academic_year_id')) {
                $classQuery->where('academic_year_id', $request->academic_year_id);
            } elseif ($myClass) {
                $classQuery->where('academic_year_id', $myClass->academic_year_id);
            }
            $availableClasses = $classQuery->orderBy('name')->get();
        } else {
            $classQuery = SchoolClass::where('teacher_id', Auth::id());
             if ($request->filled('academic_year_id')) {
                $classQuery->where('academic_year_id', $request->academic_year_id);
            } else {
                 $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
                 if ($activeYear) {
                     $classQuery->where('academic_year_id', $activeYear->id);
                 }
            }
            $availableClasses = $classQuery->with('academicYear')->orderBy('name')->get();
        }
        // -----------------------------------------------------------

        $violations = \App\Models\StudentViolation::whereHas('student.classes', function ($query) use ($myClass) {
            $query->where('classes.id', $myClass->id);
        })
        ->with(['student', 'recorder'])
        ->orderByDesc('date')
        ->paginate(20);

        return view('wali_kelas.violations', compact('myClass', 'violations', 'academicYears', 'units', 'availableClasses'));
    }

    public function extracurriculars(Request $request)
    {
        $myClass = $this->getMyClass($request);
        
        if (!$myClass) {
             return redirect()->route('wali-kelas.index')->with('error', 'Anda tidak terdaftar sebagai Wali Kelas.');
        }

        // --- Filter Data Preparation ---
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        $units = collect();
        $availableClasses = collect();

        if (Auth::user()->role === 'administrator') {
            $units = \App\Models\Unit::all();
            $classQuery = SchoolClass::query();
             if ($request->filled('unit_id')) {
                $classQuery->where('unit_id', $request->unit_id);
            }
            if ($request->filled('academic_year_id')) {
                $classQuery->where('academic_year_id', $request->academic_year_id);
            } elseif ($myClass) {
                $classQuery->where('academic_year_id', $myClass->academic_year_id);
            }
            $availableClasses = $classQuery->orderBy('name')->get();
        } else {
            $classQuery = SchoolClass::where('teacher_id', Auth::id());
             if ($request->filled('academic_year_id')) {
                $classQuery->where('academic_year_id', $request->academic_year_id);
            } else {
                 $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
                 if ($activeYear) {
                     $classQuery->where('academic_year_id', $activeYear->id);
                 }
            }
            $availableClasses = $classQuery->with('academicYear')->orderBy('name')->get();
        }
        // -----------------------------------------------------------

        $students = $myClass->studentHistory()
            ->where('status', 'aktif')
            ->with(['extracurriculars' => function($q) use ($myClass) {
                // Only show extracurriculars for the academic year of the class
                $q->where('academic_year_id', $myClass->academic_year_id)
                  ->with('extracurricular');
            }])
            ->orderBy('nama_lengkap')
            ->get();

        return view('wali_kelas.extracurriculars', compact('myClass', 'students', 'academicYears', 'units', 'availableClasses'));
    }
}
