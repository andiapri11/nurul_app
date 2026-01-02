<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Unit;
use Carbon\Carbon;

class MadingController extends Controller
{
    public function index(Request $request)
    {
        // Set locale for date display
        Carbon::setLocale('id');
        $now = Carbon::now();
        
        // Manual mapping to ensure match with DB Enum
        $dayMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $currentDayName = $dayMap[$now->format('l')];

        $unitId = $request->input('unit_id');
        
        // If logged in as mading user with assigned unit, force that unit
        if (auth()->check() && auth()->user()->role === 'mading' && auth()->user()->unit_id) {
            $unitId = auth()->user()->unit_id;
        }

        $selectedUnit = null;

        // Fetch Academic Calendar for Today
        $todayStr = $now->format('Y-m-d');
        $calendarEntries = \App\Models\AcademicCalendar::where('date', $todayStr)->get()->keyBy('unit_id');

        $query = Schedule::with(['unit', 'schoolClass', 'subject', 'teacher'])
                         ->where('day', $currentDayName)
                         ->whereHas('schoolClass', function($q) {
                             $q->whereHas('academicYear', function($sq) {
                                 $sq->where('status', 'active');
                             });
                         })
                         ->orderBy('unit_id')
                         ->orderBy('start_time');

        if ($unitId) {
            $query->where('unit_id', $unitId);
            $selectedUnit = Unit::find($unitId);
        }

        $rawSchedules = $query->get();
        $schedules = collect();
        
        // Filter Schedules based on Holiday logic
        foreach ($rawSchedules as $sch) {
            $cal = $calendarEntries[$sch->unit_id] ?? null;
            $isHoliday = false;
            
            if ($cal) {
                if ($cal->is_holiday) $isHoliday = true;
                // Acts as holiday for schedule display purposes if user says "Holiday = No Schedule"
                // But what about Activity? "kegiatan beri keterangan". 
                // Does activity mean NO regular classes? Usually yes, Activity replaces Class.
                // Let's assume Activity also suppresses regular Schedule unless specified otherwise?
                // User said: "hari efektif tampilkan jadwal", "hari libur jangan", "kegiatan beri keterangan".
                // This implies Activity != Effective (Jadwal).
                // So if Activity exists, don't show schedule, show Description.
                // Thus, if ANY calendar entry exists (Holiday OR Activity), we might skip schedule?
                // Wait, logic:
                // - Holiday: is_holiday=true.
                // - Activity: is_holiday=false, description set.
                // If Activity, do we show schedule? "kegiatan beri keterangan".
                // Usually class meeting means no regular class.
                // Let's hide schedule if calendar entry exists?
                // Default was Effective = No Record.
                
                // Let's assume if there is a calendar record, regular schedule is suspended.
                // Exception: Maybe some activities run parallel? 
                // Given the user request "otomatis jika hari efektif tampilkan jadwal", implies non-effective (holiday/activity) -> no jadawal.
            } else {
                // If no record, check weekend
                if ($now->isWeekend()) { 
                    // Weekend logic handled by User setting Saturdays as Holiday manually now?
                    // Or default check? 
                    // Earlier we said Saturdays are default Holiday. 
                    // If no record and it is Sat/Sun -> Holiday.
                    // But if user didn't put it in DB, we rely on check.
                    if ($now->isSunday() || $now->isSaturday()) {
                         $isHoliday = true;
                    }
                }
            }
            
            if (!$isHoliday && !$cal) {
                // Effective day
                $schedules->push($sch);
            }
             // If $cal exists (Holiday or Activity) -> Schedule NOT pushed.
        }
        
        // Load attendance status for Today (only for kept schedules)
        foreach($schedules as $schedule) {
            $schedule->todayCheckin = \App\Models\ClassCheckin::where('schedule_id', $schedule->id)
                ->whereDate('checkin_time', $now->format('Y-m-d'))
                ->first();
        }

        $units = Unit::all();

        // Fetch announcements
        $announcementQuery = \App\Models\Announcement::where('is_active', true);
        if ($unitId) {
            $announcementQuery->where('unit_id', $unitId);
        }
        $allAnnouncements = $announcementQuery->get();
        
        $runningText = $allAnnouncements->where('type', 'running_text');
        $news = $allAnnouncements->whereIn('type', ['news', 'poster']);
        
        // Prepare Calendar Info for View (Effective/Holiday/Activity Status per Unit)
        // If UnitId is selected, we just pass that one status.
        // If All Units, maybe we need to show a general message or per unit?
        // View Mading usually has a header.
        
        $dayStatus = 'effective'; 
        $dayDescription = '';
        
        if ($selectedUnit) {
            $cal = $calendarEntries[$selectedUnit->id] ?? null;
            if ($cal) {
                if ($cal->is_holiday) {
                    $dayStatus = 'holiday';
                    $dayDescription = $cal->description ?? 'Hari Libur';
                } else {
                    $dayStatus = 'activity';
                    $dayDescription = $cal->description;
                }
            } elseif ($now->isSunday() || $now->isSaturday()) {
                $dayStatus = 'holiday';
                $dayDescription = 'Libur Akhir Pekan';
            }
        }

        // Fetch absent students (excluding 'present' and 'school_activity'?) 
        // User request: "tampilkan absen siswa yang tidak hadir saja"
        // Valid non-present statuses: sick, permission, alpha, late (maybe late is present but late? usually shown as non-perfect attendance).
        // Let's assume: sick, permission, alpha. Late is physically present.
        // Also filter by Today and Active Year.
        
        $absentQuery = \App\Models\StudentAttendance::with(['student', 'schoolClass'])
                        ->whereDate('date', $todayStr)
                        ->whereIn('status', ['sick', 'permission', 'alpha']); // Exclude late/present/activity
                        
        if ($unitId) {
             $absentQuery->whereHas('schoolClass', function($q) use ($unitId) {
                 $q->where('unit_id', $unitId);
             });
        }
        
        $absences = $absentQuery->get()
                    ->groupBy(function($item) {
                        return $item->schoolClass->name;
                    });

        return view('mading.index', compact('schedules', 'units', 'currentDayName', 'now', 'selectedUnit', 'runningText', 'news', 'dayStatus', 'dayDescription', 'absences'));
    }
}
