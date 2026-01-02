<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicCalendar;

class AcademicCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $managementUnits = $user->getLearningManagementUnits();
        $isManagement = in_array($user->role, ['administrator', 'direktur']) || $managementUnits->isNotEmpty();
        
        // A user is treated as a read-only guru if they HAVE the role and NO management units
        $isGuruReadOnly = ($user->role === 'guru' && !$isManagement);

        // Authorization: Admin, Management, or Guru
        if (!$isManagement && !$isGuruReadOnly) {
              abort(403, 'Akses ditolak.'); 
        }
        
        // Fetch Units based on priority: Admin/Management > Guru
        if (in_array($user->role, ['administrator', 'direktur'])) {
            $units = \App\Models\Unit::all();
        } elseif ($isManagement) {
            $units = $managementUnits;
        } else {
            $units = $user->getTeachingUnits();
        }
        
        $allAcademicYears = \App\Models\AcademicYear::orderByDesc('start_year')->get();
        $activeAY = \App\Models\AcademicYear::where('status', 'active')->first();

        // Defaults
        $unit_id = $request->get('unit_id', $units->first()->id ?? null);
        
        // Academic Year Filter - Strict for Read-only Guru
        if ($isGuruReadOnly) {
            $academic_year_id = $activeAY->id ?? $allAcademicYears->first()->id ?? null;
            $academicYears = collect(); // Only show active one in dropdown
            if ($activeAY) $academicYears->push($activeAY);
        } else {
            $academicYears = $allAcademicYears;
            $academic_year_id = $request->get('academic_year_id', $activeAY->id ?? $allAcademicYears->first()->id ?? null);
        }
        
        $academicYear = $academicYears->firstWhere('id', $academic_year_id);
        
        // Semester Filter
        $semester = $request->get('semester', 'ganjil'); // ganjil, genap
        
        // Determine Month/Year logic based on selection
        // If user changed semester/AY, we might need to reset 'month' to start of that semester
        $defaultMonth = ($semester == 'ganjil') ? 7 : 1;
        $defaultYear = ($semester == 'ganjil') ? $academicYear->start_year : $academicYear->end_year;
        
        // If checking specific month, use it. But ensure it's within range? 
        // For flexibility, we allow any month view, but typically user filters inside the AY.
        // Let's force the calendar to view the selected month if provided, 
        // OR default to current month IF it is inside the semester, 
        // OR default to start of semester.
        
        $reqMonth = $request->get('month');
        $reqYear = $request->get('year');
        
        if ($reqMonth && $reqYear) {
            $month = (int)$reqMonth;
            $year = (int)$reqYear;
        } else {
            // Check if "now" is inside the selected semester
            $now = now();
            $startSem = \Carbon\Carbon::createFromDate($defaultYear, ($semester=='ganjil' ? 7 : 1), 1);
            $endSem = $startSem->copy()->addMonths(5)->endOfMonth();
            
            if ($now->between($startSem, $endSem)) {
                $month = $now->month;
                $year = $now->year;
            } else {
                $month = $defaultMonth;
                $year = $defaultYear;
            }
        }

        // Fetch Grid Events (For the SINGLE MONTH view)
        $events = collect();
        if ($unit_id) {
            $events = AcademicCalendar::where('unit_id', $unit_id)
                        ->whereMonth('date', $month)
                        ->whereYear('date', $year)
                        ->get()
                        ->keyBy(fn($item) => $item->date->format('Y-m-d'));
        }

        // --- Calculate Stats (For the WHOLE SEMESTER) ---
        // Range
        $semStartYear = ($semester == 'ganjil') ? $academicYear->start_year : $academicYear->end_year;
        $semStartMonth = ($semester == 'ganjil') ? 7 : 1;
        
        $semStartDate = \Carbon\Carbon::createFromDate($semStartYear, $semStartMonth, 1);
        $semEndDate = $semStartDate->copy()->addMonths(5)->endOfMonth(); // 6 months duration
        
        // Get all events in this semester
        $semEvents = AcademicCalendar::where('unit_id', $unit_id)
                        ->whereBetween('date', [$semStartDate, $semEndDate])
                        ->get()
                        ->keyBy(fn($item) => $item->date->format('Y-m-d'));
        
        $semesterStats = [
            'effective' => 0,
            'holiday' => 0,
            'activity' => 0
        ];
        
        // Iterate every day in semester
        $period = \Carbon\CarbonPeriod::create($semStartDate, $semEndDate);
        foreach ($period as $dt) {
            $dStr = $dt->format('Y-m-d');
            $evt = $semEvents[$dStr] ?? null;
            $isWeekend = ($dt->dayOfWeek === \Carbon\Carbon::SUNDAY || $dt->dayOfWeek === \Carbon\Carbon::SATURDAY);
            
            if ($evt) {
                if ($evt->is_holiday) $semesterStats['holiday']++;
                else $semesterStats['activity']++;
            } elseif ($isWeekend) {
                $semesterStats['holiday']++;
            } else {
                $semesterStats['effective']++;
            }
        }
        
        // Also calculate Monthly Stats (for the view title or small info if needed, but existing view uses 'stats' variable)
        // The user asked for RECAP. Usually Semantic recap implies the broader scope.
        // But the View currently looks at `$stats` which was Monthly. 
        // Let's pass `$semesterStats` as the main `stats` to the view? 
        // "BUAT REKAP ... MENGETAHUI JUMLAH ... HARI EFEKTIF" often implies Semester count for rapport.
        // Let's overwrite `stats` with `semesterStats` AND pass `monthStats` separately if needed.
        // Actually, clearer to label it.
        
        return view('academic_calendars.index', compact(
            'events', 'units', 'academicYears', 'academic_year_id', 'semester', 
            'unit_id', 'month', 'year', 'semesterStats', 'semStartDate', 'semEvents', 'isGuruReadOnly'
        ));
    }

    public function create()
    {
        $user = auth()->user();
        
        if (!$user->isDirektur() && $user->getLearningManagementUnits()->isEmpty()) {
             abort(403, 'Anda tidak diizinkan membuat agenda.');
        }
        
        if ($user->isDirektur()) {
            $units = \App\Models\Unit::all();
        } else {
             // Filter for Kurikulum
             if ($user->getLearningManagementUnits()->isEmpty()) abort(403);
             
             // Get units where user is Kurikulum + Own Unit
             $units = $user->getLearningManagementUnits();
             // Fallback if needed
             if ($units->isEmpty() && $user->unit_id) {
                 // Maybe check if they are Head of Unit? Or just fail. 
                 // Strict: fail or show empty.
             }
        }
        return view('academic_calendars.create', compact('units'));
    }

    public function store(Request $request)
    {
        // Authorization Check
        $user = auth()->user();

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'description' => 'required|string|max:255',
            'is_holiday' => 'boolean',
        ]);

        if (!$user->isDirektur()) {
             if (!$user->isLearningManagerForUnit($request->unit_id)) {
                 abort(403, 'Anda tidak berhak mengelola unit ini.');
             }
        }

        $startDate = \Carbon\Carbon::parse($request->date_start);
        $endDate = $request->date_end ? \Carbon\Carbon::parse($request->date_end) : $startDate->copy();
        
        $insertedCount = 0;
        $skippedCount = 0;

        // Iterate through dates
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $currentDateStr = $date->format('Y-m-d');
            
            // Check existence
            $exists = AcademicCalendar::where('unit_id', $request->unit_id)
                        ->where('date', $currentDateStr)
                        ->exists();
            
            if (!$exists) {
                AcademicCalendar::create([
                    'unit_id' => $request->unit_id,
                    'date' => $currentDateStr,
                    'description' => $request->description,
                    'is_holiday' => $request->boolean('is_holiday', true), // Default to true if not present, but checkbox usually sends 0/1
                ]);
                $insertedCount++;
            } else {
                $skippedCount++;
            }
        }

        $message = "Berhasil menambahkan $insertedCount agenda.";
        if ($skippedCount > 0) {
            $message .= " ($skippedCount tanggal dilewati karena sudah ada)";
        }

        return redirect()->route('academic-calendars.index', ['unit_id' => $request->unit_id])->with('success', $message);
    }

    public function destroy(AcademicCalendar $academicCalendar)
    {
        // Authorization for delete
        $user = auth()->user();
        
        if (!$user->isDirektur()) {
            if (!$user->isLearningManagerForUnit($academicCalendar->unit_id)) abort(403);
        }
        
        $academicCalendar->delete();
        return redirect()->route('academic-calendars.index')->with('success', 'Agenda berhasil dihapus.');
    }

    // --- Monthly Management Feature ---

    public function manage(Request $request)
    {
        $user = auth()->user();
        
        // Authorization: Admin or Kurikulum
        if (!$user->isDirektur() && $user->getLearningManagementUnits()->isEmpty()) {
            abort(403);
        }

        // Fetch Units based on authority
        if ($user->isDirektur()) {
            $units = \App\Models\Unit::all();
        } else {
            $units = $user->getLearningManagementUnits();
        }
        
        $unit_id = $request->get('unit_id', $units->first()->id ?? null);
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);
        
        // Validation: Verify Kurikulum has access to requested unit
        if ($user->role !== 'administrator' && $unit_id) {
            if (!$user->isLearningManagerForUnit($unit_id)) {
                abort(403, 'Anda tidak memiliki akses ke unit ini.');
            }
        }
        
        if (!$unit_id) return redirect()->route('dashboard')->with('error', 'Tidak ada unit sekolah yang dapat dikelola.');

        $currentUnit = $units->find($unit_id);
        
        // Generate dates for the month
        $startDate = \Carbon\Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $dates = [];
        
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->copy();
        }

        // Fetch existing records for this month and unit
        $existingRecords = AcademicCalendar::where('unit_id', $unit_id)
                            ->whereMonth('date', $month)
                            ->whereYear('date', $year)
                            ->get()
                            ->keyBy(function($item) {
                                return $item->date->format('Y-m-d');
                            });

        $calendarData = $existingRecords;

        return view('academic_calendars.manage', compact('units', 'currentUnit', 'month', 'year', 'dates', 'calendarData'));
    }

    public function updateMonth(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'days' => 'array', // Array of [date => [status, description]]
        ]);
        
        $unit_id = $request->unit_id;
        $user = auth()->user();

        // Authorization Check
        if (!$user->isDirektur()) {
            if (!$user->isLearningManagerForUnit($unit_id)) {
                 abort(403, 'Anda tidak berhak mengedit unit ini.');
            }
        }
        
        foreach ($request->days as $dateStr => $data) {
            $status = $data['status'] ?? 'effective'; // effective, holiday, activity
            $description = $data['description'] ?? null;
            
            // Check if record exists
            $record = AcademicCalendar::where('unit_id', $unit_id)->where('date', $dateStr)->first();
            
            if ($status === 'effective') {
                if ($record) {
                    $record->delete();
                }
            } else {
                // Holiday or Activity
                $isHoliday = ($status === 'holiday');
                
                // If description is empty for holiday, maybe set default
                if ($status === 'holiday' && empty($description)) $description = 'Libur';
                if ($status === 'activity' && empty($description)) $description = 'Kegiatan Sekolah';
                
                AcademicCalendar::updateOrCreate(
                    ['unit_id' => $unit_id, 'date' => $dateStr],
                    ['is_holiday' => $isHoliday, 'description' => $description]
                );
            }
        }
        
        return redirect()->route('academic-calendars.manage', [
            'unit_id' => $unit_id,
            'month' => $request->month,
            'year' => $request->year
        ])->with('success', 'Kalender bulan ini berhasil diperbarui.');
    }
}
