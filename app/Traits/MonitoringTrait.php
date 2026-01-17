<?php

namespace App\Traits;

use App\Models\Schedule;
use App\Models\Unit;
use App\Models\ClassCheckin;
use App\Models\AcademicCalendar;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait MonitoringTrait
{
    /**
     * Get monitoring data for "Mading-like" dashboard widget.
     * 
     * @param int|string|null $unitId
     * @return array
     */
    protected function getMonitoringData($unitId = null)
    {
        Carbon::setLocale('id');
        $now = Carbon::now();
        $todayStr = $now->format('Y-m-d');

        // 1. Day Management
        $dayMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];
        $currentDayName = $dayMap[$now->format('l')];

        // 2. Fetch Calendars
        $calendarEntries = AcademicCalendar::where('date', $todayStr)->get()->keyBy('unit_id');

        // 3. Fetch Schedules (With Eager Loading to prevent N+1)
        $query = Schedule::with(['unit', 'schoolClass', 'subject', 'teacher', 'todayCheckin'])
            ->where('day', $currentDayName)
            ->whereHas('schoolClass', function ($q) {
                $q->whereHas('academicYear', function ($sq) {
                    $sq->where('status', 'active');
                });
            })
            ->orderBy('start_time');

        if ($unitId && $unitId !== 'all') {
            $query->where('unit_id', $unitId);
        }

        $rawSchedules = $query->get();
        $schedules = collect();

        // 4. Filter by Holiday Logic
        foreach ($rawSchedules as $sch) {
            $cal = $calendarEntries[$sch->unit_id] ?? $calendarEntries[''] ?? null;
            $isHoliday = false;

            if ($cal) {
                if ($cal->is_holiday) $isHoliday = true;
            } else {
                if ($now->isSunday() || $now->isSaturday()) {
                    $isHoliday = true;
                }
            }

            if (!$isHoliday && (!$cal || !$cal->is_holiday)) {
                $schedules->push($sch);
            }
        }

        // 5. Load Checkins (Already Eager Loaded above)

        // 6. Group Schedules
        $groupedSchedules = $schedules->groupBy(function ($item) {
            return $item->schoolClass ? $item->schoolClass->name : 'Lainnya';
        })->sortKeys();

        // 7. Day Status Calculation
        $dayStatus = 'effective';
        $dayDescription = '';

        // Check for Global Calendar Event (Null/Empty unit_id)
        $globalCal = $calendarEntries[''] ?? null;
        
        if ($globalCal) {
            if ($globalCal->is_holiday) {
                $dayStatus = 'holiday';
                $dayDescription = $globalCal->description ?? 'Libur Nasional/Cuti Bersama';
            } else {
                $dayStatus = 'activity';
                $dayDescription = $globalCal->description;
            }
        }

        // Check for Specific Unit Event (Overrides Global if exists, or adds to it? 
        // For now, let's say Unit Holiday overrides Global Activity, but Global Holiday overrides Unit Activity generally.
        // Simple logic: If we are viewing specific unit, prioritize that unit's calendar.)
        if ($unitId && $unitId !== 'all') {
            $unitCal = $calendarEntries[$unitId] ?? null;
            if ($unitCal) {
                if ($unitCal->is_holiday) {
                    $dayStatus = 'holiday';
                    $dayDescription = $unitCal->description ?? 'Libur Unit';
                } else {
                    // If it's not a holiday, it's an activity. 
                    // Only overwrite global status if global was NOT a holiday.
                    if ($dayStatus !== 'holiday') {
                        $dayStatus = 'activity';
                        $dayDescription = $unitCal->description;
                    }
                }
            }
        }

        // Weekend Check (Saturday/Sunday)
        // Only applies if status is NOT ALREADY a holiday or activity from calendar.
        // (Or should weekend override activity? Usually Calendar overrides Weekend).
        if ($dayStatus === 'effective' && ($now->isSunday() || $now->isSaturday())) {
            $dayStatus = 'holiday';
            $dayDescription = 'Libur Akhir Pekan';
        }

        return [
            'groupedSchedules' => $groupedSchedules,
            'currentDayName' => $currentDayName,
            'now' => $now,
            'dayStatus' => $dayStatus,
            'dayDescription' => $dayDescription,
        ];
    }
}
