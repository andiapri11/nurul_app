<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Carbon\Carbon;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch detailed assignments for display
        $assignments = $user->jabatanUnits()->with(['jabatan', 'unit'])->get();
        
        // 1. Ambil Jadwal Hari Ini
        // Carbon::setLocale('id'); // Sudah diset di config/app.php
        $today = Carbon::now()->isoFormat('dddd'); // Senin, Selasa...
        
        // Scope to Active Academic Year
        $activeYearId = \App\Models\AcademicYear::active()->value('id');
        
        $schedules = Schedule::where('user_id', $user->id)
            ->where('day', $today)
            ->whereHas('schoolClass', function($q) use ($activeYearId) {
                $q->where('academic_year_id', $activeYearId);
            })
            ->with(['schoolClass', 'subject'])
            ->orderBy('start_time')
            ->get();

        // 2. Cek apakah ada jadwal aktif SAAT INI (Live Teaching)
        $now = Carbon::now()->format('H:i:s');
        $currentSchedule = Schedule::where('user_id', $user->id)
            ->where('day', $today)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->whereHas('schoolClass', function($q) use ($activeYearId) {
                $q->where('academic_year_id', $activeYearId);
            })
            ->with(['schoolClass', 'subject', 'todayCheckin'])
            ->first();

        // 3. Info Walas
        // Assuming waliKelasOf relationship on User model points to Class, we need to ensure that class is also in active AY if strictly enforced,
        // but typically a teacher is only walas of one class at a time. However, if history is kept, we might need filtering.
        // For now, let's assume strict 1 active class per walas or simple relation.
        // Better to check if the class assigned belongs to active year?
        $waliKelas = $user->waliKelasOf;
        if ($waliKelas && $waliKelas->academic_year_id != $activeYearId) {
            $waliKelas = null; // Don't show old walas data
        }
        
        $totalSiswaWalas = $waliKelas ? $waliKelas->students()->count() : 0;

        // 4. Statistik Jadwal Minggu Ini
        // 4. Statistik Jadwal Minggu Ini & Total Siswa Ajar
        $taughtClassIds = Schedule::where('user_id', $user->id)
             ->whereHas('schoolClass', function($q) use ($activeYearId) {
                $q->where('academic_year_id', $activeYearId);
            })->pluck('class_id')->unique();
            
        $totalJadwal = Schedule::where('user_id', $user->id)
             ->whereHas('schoolClass', function($q) use ($activeYearId) {
                $q->where('academic_year_id', $activeYearId);
            })->count();

        $totalKelasAjar = $taughtClassIds->count();
        $totalSiswaAjar = 0;
        if ($totalKelasAjar > 0) {
            $totalSiswaAjar = \App\Models\Student::whereHas('classes', function($q) use ($taughtClassIds, $activeYearId) {
                                    $q->whereIn('classes.id', $taughtClassIds)
                                      ->where('classes.academic_year_id', $activeYearId);
                                })
                                ->where('status', 'aktif')
                                ->count();
        }

        // 5. Cek apakah sudah absen hari ini (hanya jika wali kelas)
        $attendanceMissing = false;
        if ($waliKelas) {
            $todayDate = Carbon::now()->format('Y-m-d');
            $attendanceCount = \App\Models\StudentAttendance::where('class_id', $waliKelas->id)
                                ->where('date', $todayDate)
                                ->count();
            
            if ($attendanceCount == 0 && $totalSiswaWalas > 0) {
                $attendanceMissing = true;
            }
        }

        // 6. smart Schedule Filtering based on Calendar
        $dateStr = Carbon::now()->format('Y-m-d');
        
        // Fetch all calendar entries for today
        // 6. Fetch Calendar Activities for Today
        $dateStr = Carbon::now()->toDateString();
        $calendars = \App\Models\AcademicCalendar::where('date', $dateStr)->get()->groupBy('unit_id');
        $globalCals = \App\Models\AcademicCalendar::whereNull('unit_id')->where('date', $dateStr)->get();

        // Helper to find the best matching calendar entry for a class
        $findBestCal = function($unitId, $classId) use ($calendars, $globalCals) {
            $unitCals = $calendars->get($unitId, collect())->merge($globalCals);
            
            // Priority 1: Specific Holiday
            $specHol = $unitCals->first(fn($c) => $c->is_holiday && is_array($c->affected_classes) && in_array($classId, $c->affected_classes));
            if ($specHol) return $specHol;
            
            // Priority 2: Specific Activity
            $specAct = $unitCals->first(fn($c) => !$c->is_holiday && is_array($c->affected_classes) && in_array($classId, $c->affected_classes));
            if ($specAct) return $specAct;
            
            // Priority 3: Global Holiday
            $globHol = $unitCals->first(fn($c) => $c->is_holiday && is_null($c->affected_classes));
            if ($globHol) return $globHol;
            
            // Priority 4: Global Activity
            $globAct = $unitCals->first(fn($c) => !$c->is_holiday && is_null($c->affected_classes));
            if ($globAct) return $globAct;
            
            return null;
        };

        // Filter Query Schedules
        $filteredSchedules = $schedules->filter(function($schedule) use ($findBestCal) {
            $unitId = $schedule->schoolClass->unit_id;
            $classId = $schedule->class_id;
            
            $cal = $findBestCal($unitId, $classId);
            
            if ($cal) {
                // Remove if Holiday
                if ($cal->is_holiday) return false;
                
                // If Activity, keep but flag description
                $schedule->calendar_activity = $cal->description;
            }
            return true;
        });
        
        // Determine Global Status for Dashboard Display
        // If we filtered out ALL schedules due to holidays, then it is a Holiday for this teacher.
        // If we have mixed, it's effective (showing only effective ones).
        // If no schedules originally, check unit specific or global.
        
        $isHoliday = false;
        $isActivity = false;
        $calendarDescription = '';
        
        // Multi-Unit Status Logic
        // We want to know the status of EACH unit the teacher is associated with.
        
        // 1. Collect Unit IDs from various sources
        $teacherUnitIds = collect();
        if ($user->unit_id) $teacherUnitIds->push($user->unit_id);
        if ($waliKelas) $teacherUnitIds->push($waliKelas->unit_id);
        
        // From today's schedules
        if ($schedules->isNotEmpty()) {
            $teacherUnitIds = $teacherUnitIds->merge($schedules->pluck('schoolClass.unit_id'));
        }
        
        // From ALL active schedules (to capture units even if no schedule today)
        $allActiveSchedules = Schedule::where('user_id', $user->id)
            ->whereHas('schoolClass', function($q) use ($activeYearId) {
                $q->where('academic_year_id', $activeYearId);
            })
            ->with(['schoolClass'])
            ->get();
        if ($allActiveSchedules->isNotEmpty()) {
            $teacherUnitIds = $teacherUnitIds->merge($allActiveSchedules->pluck('schoolClass.unit_id'));
        }

        // From Jabatan (if exists)
        $jabatanUnits = \Illuminate\Support\Facades\DB::table('user_jabatan_units')
                            ->where('user_id', $user->id)
                            ->pluck('unit_id');
        $teacherUnitIds = $teacherUnitIds->merge($jabatanUnits);

        $teacherUnitIds = $teacherUnitIds->unique()->filter()->values();
        
        // 2. Build Status for each Unit
        $unitStatuses = [];
        if ($teacherUnitIds->isNotEmpty()) {
            $units = \App\Models\Unit::whereIn('id', $teacherUnitIds)->get()->keyBy('id');
            // Fetch calendars for these units (already have $calendars for today)
            // But we need to ensure $calendars covers all units, so let's refetch or rely on what we have? 
            // We fetched `where('date', $dateStr)` without unit constraint, so `calendars` has ALL units for today. Safe.
            
            foreach ($teacherUnitIds as $uid) {
                $unitName = $units[$uid]->name ?? 'Unit ' . $uid;
                
                // For Unit overview, try to find Unit-wide holiday/activity first
                $unitCals = $calendars->get($uid, collect())->merge($globalCals);
                
                // Check if there is any unit-wide holiday first, then unit-wide activity
                $unitWideHol = $unitCals->first(fn($c) => $c->is_holiday && is_null($c->affected_classes));
                $unitWideAct = $unitCals->first(fn($c) => !$c->is_holiday && is_null($c->affected_classes));
                
                // If no unit-wide, check if there's any holiday at all for this unit (partial)
                $anyHol = $unitCals->first(fn($c) => $c->is_holiday);
                $anyAct = $unitCals->first(fn($c) => !$c->is_holiday);

                $status = 'effective';
                $desc = 'Hari Efektif';
                
                $targetCal = $unitWideHol ?? $unitWideAct ?? $anyHol ?? $anyAct;

                if ($targetCal) {
                    if ($targetCal->is_holiday) {
                        $status = 'holiday';
                        $desc = $targetCal->description;
                    } else {
                        $status = 'activity';
                        $desc = $targetCal->description;
                    }
                } elseif (Carbon::now()->isWeekend()) {
                     $status = 'holiday';
                     $desc = 'Libur Akhir Pekan';
                }
                
                $unitStatuses[] = [
                    'unit' => $unitName,
                    'status' => $status,
                    'description' => $desc
                ];
            }
        }
        
        
        // 3. Fallback for Global Status (compatible with previous view logic, but mostly superseded by Unit Banners)
        if ($schedules->isNotEmpty() && $filteredSchedules->isEmpty()) {
            // All scheduled classes are holiday -> Global Holiday
            $isHoliday = true;
            // Use description from first holiday unit
             $firstUnitId = $schedules->first()->schoolClass->unit_id;
             $cal = $calendars->get($firstUnitId, collect())->first() ?? $globalCals->first();
             $calendarDescription = $cal ? $cal->description : 'Libur';
        } elseif ($filteredSchedules->isNotEmpty()) {
             // Mixed or all effective
             // If ANY unit has activity, maybe highlight?
             // But existing logic was: if schedules exist, show them.
        } else {
            // No schedules originally. Use Unit Statuses to decide "Global" feel
            // If ALL units are holiday -> Global Holiday
            // If ANY unit is holiday -> Mixed?
            
            $allHoliday = !empty($unitStatuses) &&  collect($unitStatuses)->every(fn($u) => $u['status'] === 'holiday');
            if ($allHoliday) {
                $isHoliday = true;
                $calendarDescription = collect($unitStatuses)->first()['description'];
            } elseif(empty($unitStatuses) && Carbon::now()->isWeekend()) {
                 $isHoliday = true;
                 $calendarDescription = 'Libur Akhir Pekan';
            }
        }
        
        // Match check-ins to all schedules for today
        $todayStr = Carbon::now()->toDateString();
        $todayCheckins = \App\Models\ClassCheckin::where('user_id', $user->id)
            ->whereDate('checkin_time', $todayStr)
            ->get()
            ->keyBy('schedule_id');

        foreach($schedules as $sch) {
            $sch->todayCheckin = $todayCheckins->get($sch->id);
        }

        // Update $schedules to filtered version
        $schedules = $filteredSchedules;
        
        // Update Current Schedule check too
        if ($currentSchedule) {
             $unitId = $currentSchedule->schoolClass->unit_id;
             $cal = $calendars->get($unitId, collect())->first() ?? $globalCals->first();
             if ($cal && $cal->is_holiday) {
                 $currentSchedule = null;
             }
        }

        // 7. Check for Pending Document Deadlines (Curriculum)
        // Logic similar to CurriculumController: match user to targets
        $pendingDocuments = collect();
        
        // Base Query: Future or recent deadlines (e.g. last 7 days overdue + future)
        // We probably want to see ALL pending tasks regardless of date until done?
        // Let's grab requests due > 30 days ago (to avoid cluttering with ancient history) up to future.
        $cutOffDate = Carbon::now()->subDays(30);
        $docRequests = \App\Models\TeacherDocumentRequest::where('due_date', '>=', $cutOffDate)
                                                         ->orderBy('due_date', 'asc')
                                                         ->with('submissions')
                                                         ->get();

        foreach ($docRequests as $req) {
            // Check if user has already submitted successfully
            $submission = $req->submissions->where('user_id', $user->id)->first();
            
            // Status Logic
            $status = 'missing'; // Default: Belum Upload
            if ($submission) {
                // Map DB status to Display logic
                if ($submission->status == 'pending') $status = 'pending'; // Menunggu Validasi
                if ($submission->status == 'validated') $status = 'validated'; // Menunggu Approval KS
                if ($submission->status == 'approved') $status = 'approved'; // Selesai
                if ($submission->status == 'rejected') $status = 'rejected'; // Ditolak (Perlu Revisi)
            }
            
            // User Filter: Show if Action Needed (Missing/Rejected) OR Waiting (Pending/Validated).
            // Hide if Approved (Done).
            if ($status == 'approved') {
                 continue; 
            }
            
            // Check if user is targeted (Same logic)
            // ... (Logic is inside loop, need to preserve targeting check)
            
            // Check if user is targeted using the model method
            $isTarget = $req->isTargetFor($user);
            
            if ($isTarget) {
                $req->user_status = $status; 
                $pendingDocuments->push($req);
            }
        }


        // Fix Attendance Missing Alert based on Holiday Status
        if ($attendanceMissing && $waliKelas) {
             $wkUnitId = $waliKelas->unit_id;
             $wkClassId = $waliKelas->id;
             $cal = $findBestCal($wkUnitId, $wkClassId);
             
             if ($cal) {
                 // If Holiday -> No attendance needed
                 if ($cal->is_holiday) {
                     $attendanceMissing = false;
                 } 
                 // If Activity -> User said "tetap wajib", so we do NOT set $attendanceMissing to false.
             } elseif (Carbon::now()->isWeekend()) {
                  $attendanceMissing = false;
             }
        }

        return view('dashboard.guru', compact(
            'user', 
            'today', 
            'schedules', 
            'currentSchedule',
            'waliKelas',
            'totalSiswaWalas',
            'totalJadwal',
            'totalKelasAjar',
            'totalSiswaAjar',
            'attendanceMissing',
            'isHoliday',
            'isActivity',
            'calendarDescription',
            'unitStatuses',
            'assignments',
            'pendingDocuments'
        ));
    }

    public function myClass()
    {
        $user = Auth::user();
        $kelas = $user->waliKelasOf;

        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai Wali Kelas.');
        }

        // Ambil data siswa di kelas ini
        $students = $kelas->students()->orderBy('nama_lengkap')->get();

        return view('dashboard.my_class', compact('user', 'kelas', 'students'));
    }
}
