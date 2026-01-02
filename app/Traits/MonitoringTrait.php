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

        // 3. Fetch Schedules
        $query = Schedule::with(['unit', 'schoolClass', 'subject', 'teacher'])
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

        // 5. Load Checkins
        foreach ($schedules as $schedule) {
            $schedule->todayCheckin = ClassCheckin::where('schedule_id', $schedule->id)
                ->whereDate('checkin_time', $todayStr)
                ->first();
        }

        // 6. Group Schedules
        $groupedSchedules = $schedules->groupBy(function ($item) {
            return $item->schoolClass ? $item->schoolClass->name : 'Lainnya';
        })->sortKeys();

        // 7. Day Status for specific unit if requested
        $dayStatus = 'effective';
        $dayDescription = '';
        if ($unitId && $unitId !== 'all') {
            $cal = $calendarEntries[$unitId] ?? $calendarEntries[''] ?? null;
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

        return [
            'groupedSchedules' => $groupedSchedules,
            'currentDayName' => $currentDayName,
            'now' => $now,
            'dayStatus' => $dayStatus,
            'dayDescription' => $dayDescription,
        ];
    }
}
