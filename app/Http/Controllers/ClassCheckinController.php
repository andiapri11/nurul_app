<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassCheckinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\ClassCheckin::query();

        // Admin/Staff logic: see all. Others: see own.
        // Unified Filtering for ALL roles based on user request "semua role ... berlakukan"
        
        $query->with(['user', 'schedule.schoolClass', 'schedule.subject', 'schedule.schoolClass.unit', 'schedule.schoolClass.academicYear']);

        $query->with(['user', 'schedule.schoolClass', 'schedule.subject', 'schedule.schoolClass.unit', 'schedule.schoolClass.academicYear']);

        $user = auth()->user();
        // Allow Administrator, Staff, AND Kurikulum to see all. Others see only their own.
        if (!in_array($user->role, ['administrator', 'staff']) && !$user->isKurikulum()) {
             $query->where('user_id', $user->id);
        }

        // Filter: Unit
        if ($request->filled('unit_id')) {
            $query->whereHas('schedule.schoolClass', function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        // Filter: Tahun Pelajaran (Academic Year)
        // If explicit ID is provided (and not 'all')
        if ($request->filled('academic_year_id') && $request->academic_year_id !== 'all') {
            $query->whereHas('schedule.schoolClass', function($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        } elseif ($request->academic_year_id === 'all') {
            // Do nothing, show all history
        } else {
            // Default: Show only Active Academic Year if no specific filter OR if empty
            $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
            if ($activeYear) {
                $query->whereHas('schedule.schoolClass', function($q) use ($activeYear) {
                    $q->where('academic_year_id', $activeYear->id);
                });
            }
        }

        // Filter: Kelas
        if ($request->filled('class_id')) {
            $query->whereHas('schedule', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }
        
        // Filter: Tanggal (Single Date - Priority)
        if ($request->filled('date')) {
            $query->whereDate('checkin_time', $request->date);
        } else {
            // Filter: Date Range
            if ($request->filled('start_date')) {
                    $query->whereDate('checkin_time', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                    $query->whereDate('checkin_time', '<=', $request->end_date);
            }
        }

        $checkins = $query->latest()->paginate(10);
        
        // Data for filters
        // Units Logic
        if (in_array($user->role, ['administrator', 'staff'])) {
            $units = \App\Models\Unit::all();
        } else {
            // 1. Units where user teaches (Schedules)
            $teachingUnitIds = \App\Models\Schedule::where('user_id', $user->id)
                ->join('classes', 'schedules.class_id', '=', 'classes.id')
                ->pluck('classes.unit_id')
                ->unique()
                ->toArray();

            // 2. Units where user is Kurikulum (if applicable)
            $managementUnitIds = [];
            if ($user->isKurikulum()) {
                $managementUnitIds = $user->getKurikulumUnits()->pluck('id')->toArray();
            }

            // 3. Home Unit
            $homeUnitId = $user->unit_id ? [$user->unit_id] : [];

            $allowedUnitIds = array_unique(array_merge($teachingUnitIds, $managementUnitIds, $homeUnitId));
            
            $units = \App\Models\Unit::whereIn('id', $allowedUnitIds)->get();
        }
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        // Classes should ideally be filtered by Unit if Unit is selected, but fetching all for now or handling in view
        // Classes Logic
        $classesQuery = \App\Models\SchoolClass::orderBy('name');
        
        // If Teacher (not admin/staff/kurikulum), only show classes they teach
        // Curriculum can see all classes to filter reports
        if (!in_array($user->role, ['administrator', 'staff']) && !$user->isKurikulum()) {
            $classesQuery->whereHas('schedules', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $activeYearId = $activeYear ? $activeYear->id : null;

        if ($request->filled('academic_year_id') && $request->academic_year_id !== 'all') {
            $classesQuery->where('academic_year_id', $request->academic_year_id);
        } elseif (!$request->filled('academic_year_id')) {
             if ($activeYear) {
                 $classesQuery->where('academic_year_id', $activeYear->id);
             }
        }

        $classes = $classesQuery->get();

        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $activeYearId = $activeYear ? $activeYear->id : null;

        return view('class_checkins.index', compact('checkins', 'units', 'academicYears', 'classes', 'activeYearId'));
    }

    public function create()
    {
        // Show today's schedule for the teacher to check in
        $userId = auth()->id();
        $dayMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $today = $dayMap[now()->format('l')];

        $schedules = \App\Models\Schedule::where('user_id', $userId)
            ->where('day', $today)
            ->with(['schoolClass', 'subject'])
            ->orderBy('start_time')
            ->get();
            
        // Check if already checked in today for each schedule
        foreach($schedules as $schedule) {
            $schedule->hasCheckedIn = \App\Models\ClassCheckin::where('schedule_id', $schedule->id)
                ->whereDate('created_at', now()->today())
                ->exists();
        }

        // 6. smart Schedule Filtering based on Calendar
        $dateStr = now()->format('Y-m-d');
        
        // Fetch all calendar entries for today
        $calendars = \App\Models\AcademicCalendar::where('date', $dateStr)->get()->keyBy('unit_id');
        $globalCal = \App\Models\AcademicCalendar::whereNull('unit_id')->where('date', $dateStr)->first();

        // Calculate Multi-Unit Statuses for Feedback
        $teacherUnitIds = collect();
         // From today's schedules
        if ($schedules->isNotEmpty()) {
            $teacherUnitIds = $teacherUnitIds->merge($schedules->pluck('schoolClass.unit_id'));
        }
        
        // Add User's primary unit (if any) to show status even if no schedule
        if (auth()->user()->unit_id) $teacherUnitIds->push(auth()->user()->unit_id);
        
        $teacherUnitIds = $teacherUnitIds->unique()->values();
        
        $unitStatuses = [];
        if ($teacherUnitIds->isNotEmpty()) {
            $units = \App\Models\Unit::whereIn('id', $teacherUnitIds)->get()->keyBy('id');
            foreach ($teacherUnitIds as $uid) {
                // Determine status for this unit
                $cal = $calendars[$uid] ?? $globalCal;
                
                $status = 'effective';
                $desc = 'Hari Efektif';
                
                if ($cal) {
                    if ($cal->is_holiday) {
                        $status = 'holiday';
                        $desc = $cal->description;
                    } else {
                        $status = 'activity';
                        $desc = $cal->description;
                    }
                } elseif (now()->isWeekend()) {
                     $status = 'holiday';
                     $desc = 'Libur Akhir Pekan';
                }
                
                $unitStatuses[] = [
                    'unit' => $units[$uid]->name ?? 'Unit ' . $uid,
                    'status' => $status,
                    'description' => $desc
                ];
            }
        }


        // Filter Query Schedules to REMOVE non-active ones
        $activeSchedules = $schedules->filter(function($schedule) use ($calendars, $globalCal) {
            $unitId = $schedule->schoolClass->unit_id;
            // Robust lookup
            $cal = $calendars[$unitId] ?? $calendars[strval($unitId)] ?? $globalCal;
            
            // If Holiday OR Activity, remove
            if ($cal) { 
                return false;
            }
            return true;
        });

        $currentTime = now()->format('H:i:s');
        
        // Final Filter: Only show if CURRENTLY in the time slot (start_time <= now <= end_time)
        $readySchedules = $activeSchedules->filter(function($schedule) use ($currentTime) {
            return $currentTime >= $schedule->start_time && $currentTime <= $schedule->end_time;
        });

        // Feedback Logic:
        // 1. Too Early: There are schedules today, but the first one hasn't started yet.
        $isTooEarly = $readySchedules->isEmpty() && $activeSchedules->filter(fn($s) => $s->start_time > $currentTime)->isNotEmpty();
        
        // 2. Finished: There were schedules, but all have ended.
        $isFinishedToday = $readySchedules->isEmpty() && $activeSchedules->filter(fn($s) => $s->end_time < $currentTime)->isNotEmpty() && !$isTooEarly;

        $nextScheduleTime = $isTooEarly ? substr($activeSchedules->filter(fn($s) => $s->start_time > $currentTime)->first()->start_time, 0, 5) : null;
        
        // Determine Status for Display
        $isHoliday = false;
        $isActivity = false;
        $calendarDescription = '';
        
        // Check Global Status using Unit Statuses
        $allHoliday = !empty($unitStatuses) && collect($unitStatuses)->every(fn($u) => $u['status'] === 'holiday');
        $allActivity = !empty($unitStatuses) && collect($unitStatuses)->every(fn($u) => $u['status'] === 'activity');
        
        if ($allHoliday) {
            $isHoliday = true;
            $calendarDescription = collect($unitStatuses)->first()['description'];
        } elseif ($allActivity) {
            $isActivity = true;
            $calendarDescription = collect($unitStatuses)->first()['description'];
        } elseif ($activeSchedules->isEmpty() && $schedules->isNotEmpty()) {
             $isHoliday = true; 
             $calendarDescription = 'Tidak ada jadwal efektif hari ini.';
        } elseif ($activeSchedules->isEmpty() && $schedules->isEmpty()) {
             if (now()->isWeekend()) {
                 $isHoliday = true;
                 $calendarDescription = 'Libur Akhir Pekan';
             }
        }
        
        $schedules = $readySchedules;
        
        $todayCheckins = \App\Models\ClassCheckin::where('user_id', $userId)
            ->whereDate('checkin_time', now()->toDateString())
            ->with(['schedule.subject', 'schedule.schoolClass'])
            ->latest()
            ->get();

        return view('class_checkins.create', compact('schedules', 'today', 'isHoliday', 'isActivity', 'calendarDescription', 'unitStatuses', 'isTooEarly', 'nextScheduleTime', 'isFinishedToday', 'todayCheckins', 'activeSchedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'nullable|image',
            'notes' => 'nullable|string'
        ]);
        
        $schedule = \App\Models\Schedule::findOrFail($request->schedule_id);

        // Check against Academic Calendar
        $unitId = $schedule->schoolClass->unit_id;
        $dateStr = now()->format('Y-m-d');
        
        $calendarEntry = \App\Models\AcademicCalendar::where('unit_id', $unitId)
                            ->where('date', $dateStr)
                            ->first();

        // Determine if it is a holiday
        $isHoliday = false;
        
        if ($calendarEntry) {
            if ($calendarEntry->is_holiday) {
                $isHoliday = true;
            }
            // If exists and !is_holiday, it is Activity -> Attendance REQUIRED (Allowed)
        } else {
            // No entry -> Check Weekend
            $dayOfWeek = now()->dayOfWeek;
            if ($dayOfWeek === \Carbon\Carbon::SUNDAY || $dayOfWeek === \Carbon\Carbon::SATURDAY) {
                $isHoliday = true;
            }
        }
        
        if ($isHoliday) {
            // User said "tidak perlu", so let's block it to keep data clean
            return redirect()->back()->with('error', 'Hari ini adalah hari libur (berdasarkan Kalender Akademik). Absensi tidak diperlukan.');
        }

        if ($request->checkin_type === 'substitute') {
            $status = 'substitute';
            $request->validate(['notes' => 'required|string|min:5'], ['notes.required' => 'Catatan wajib diisi untuk menjelaskan alasan badal.']);
        } elseif ($request->checkin_type === 'absent') {
            $status = 'absent';
            $request->validate(['notes' => 'required|string|min:5'], ['notes.required' => 'Alasan tidak masuk wajib diisi.']);
        } else {
            // Normal late logic
            // Logic check: is it time?
            $now = now();
            $scheduleStart = \Carbon\Carbon::parse($schedule->start_time);
            // Assuming schedule is for today, set date to today for accurate comparison
            $scheduleStart->setDate($now->year, $now->month, $now->day);
            
            $status = 'ontime';
            $diffInMinutes = $scheduleStart->diffInMinutes($now, false); // false = return negative if before start
            
            // Tolerance: 10 minutes after start
            if ($diffInMinutes > 10) {
                $status = 'late';
            }
        }

        $checkin = \App\Models\ClassCheckin::create([
            'schedule_id' => $request->schedule_id,
            'user_id' => auth()->id(),
            'checkin_time' => now(),
            'status' => $status,
            'notes' => $request->notes . ($status === 'late' && isset($diffInMinutes) ? ' (Terlambat ' . round($diffInMinutes) . ' menit)' : ''),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Create Mading Announcement if Late
        if ($status === 'late') {
            \App\Models\Announcement::create([
                'title' => 'Keterlambatan Guru',
                'content' => 'Guru ' . auth()->user()->name . ' terlambat masuk kelas ' . $schedule->schoolClass->name . ' (' . $schedule->subject->name . ') selama ' . round($diffInMinutes) . ' menit.',
                'image' => null, // Or maybe checkin photo?
                'category' => 'pengumuman',
                'is_active' => true,
                'user_id' => auth()->id()
            ]);
        }
        
        // Handle Base64 Photo (Camera)
        if ($request->filled('photo_base64')) {
            $image = $request->photo_base64;  // your base64 encoded
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            
            // Validate valid base64
            if (base64_decode($image, true)) {
                $imageName = 'checkin_'.time().'.jpg';
                \Illuminate\Support\Facades\Storage::disk('public')->put('checkins/' . $imageName, base64_decode($image));
                $checkin->photo = 'checkins/' . $imageName;
                $checkin->save();
            }
        } 
        // Handle Fallback File Upload
        elseif ($request->hasFile('photo')) {
             $path = $request->file('photo')->store('checkins', 'public');
             $checkin->photo = $path;
             $checkin->save();
        }

        return redirect()->route('class-checkins.index')->with('success', 'Berhasil Check-in kelas!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!in_array(auth()->user()->role, ['administrator', 'staff'])) {
            abort(403, 'Unauthorized');
        }

        $checkin = \App\Models\ClassCheckin::findOrFail($id);
        
        if ($checkin->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($checkin->photo);
        }
        
        $checkin->delete();

        return redirect()->route('class-checkins.index')->with('success', 'Data Check-in berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['administrator', 'staff']) && !$user->isKurikulum()) {
            abort(403, 'Unauthorized');
        }

        $query = \App\Models\ClassCheckin::query();
        $query->with(['user', 'schedule.schoolClass', 'schedule.subject']);

        // Filter: Unit
        if ($request->filled('unit_id')) {
            $query->whereHas('schedule.schoolClass', function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        // Filter: Tahun Pelajaran
        if ($request->filled('academic_year_id')) {
            $query->whereHas('schedule.schoolClass', function($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }

        // Filter: Kelas
        if ($request->filled('class_id')) {
            $query->whereHas('schedule', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }
        
        // Filter: Tanggal (Single Date - Priority)
        if ($request->filled('date')) {
            $query->whereDate('checkin_time', $request->date);
        } else {
            // Filter: Date Range
            if ($request->filled('start_date')) {
                 $query->whereDate('checkin_time', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                 $query->whereDate('checkin_time', '<=', $request->end_date);
            }
        }

        $checkins = $query->latest()->get(); // No pagination for export
        
        $filterSummary = [
            'academic_year' => $request->academic_year_id ? \App\Models\AcademicYear::find($request->academic_year_id)->start_year : 'Semua',
            'unit' => $request->unit_id ? \App\Models\Unit::find($request->unit_id)->name : 'Semua',
            'class' => $request->class_id ? \App\Models\SchoolClass::find($request->class_id)->name : 'Semua',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ];

        return view('class_checkins.pdf', compact('checkins', 'filterSummary'));
    }
}
