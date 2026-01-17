<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaJadwalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;
        $schedules = collect();
        $studentClass = null;
        $calendarEvents = collect();

        // Fetch Active Academic Year
        $activeYear = AcademicYear::where('status', 'active')->first();

        if ($student) {
            // Try to find the student's class for the active academic year from history
            if ($activeYear) {
                $studentClass = $student->classes()->wherePivot('academic_year_id', $activeYear->id)->first();
            }

            if ($studentClass) {
                $unitId = $studentClass->unit_id;
                $classId = $studentClass->id;

                // Show schedule if the selected class belongs to the active academic year 
                // (or if we want to allow viewing schedules for any class the student is assigned to)
                if ($activeYear && $studentClass->academic_year_id == $activeYear->id) {
                $schedules = Schedule::with(['subject', 'teacher'])
                    ->where('class_id', $classId)
                    ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                    ->orderBy('start_time')
                    ->get()
                    ->groupBy('day');

                // Fetch Academic Calendar items for the current week
                $startOfWeek = now()->startOfWeek(\Carbon\Carbon::MONDAY);
                $endOfWeek = now()->endOfWeek(\Carbon\Carbon::FRIDAY);

                $events = \App\Models\AcademicCalendar::where(function($q) use ($unitId) {
                        $q->where('unit_id', $unitId)->orWhereNull('unit_id');
                    })
                    ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                    ->get()
                    ->groupBy(fn($e) => $e->date->translatedFormat('l'));

                foreach ($events as $dayName => $dayEvents) {
                    // Priority: Class-Specific Holiday > Global Holiday > Activity
                    $holiday = $dayEvents->first(fn($e) => $e->is_holiday && is_array($e->affected_classes) && in_array($classId, $e->affected_classes));
                    if (!$holiday) $holiday = $dayEvents->first(fn($e) => $e->is_holiday && is_null($e->affected_classes));
                    
                    $activity = $dayEvents->first(fn($e) => !$e->is_holiday && is_array($e->affected_classes) && in_array($classId, $e->affected_classes));
                    if (!$activity) $activity = $dayEvents->first(fn($e) => !$e->is_holiday && is_null($e->affected_classes));
                    
                    $targetEvent = $holiday ?: $activity;
                    if ($targetEvent) {
                        $calendarEvents->put($dayName, $targetEvent);
                    }
                }
                }
            }
        }

        return view('siswa.jadwal', compact('user', 'student', 'studentClass', 'schedules', 'calendarEvents'));
    }
}
