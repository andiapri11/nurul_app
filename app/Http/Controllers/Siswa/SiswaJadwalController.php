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

                $events = \App\Models\AcademicCalendar::where('unit_id', $unitId)
                    ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                    ->get();

                foreach ($events as $event) {
                    $dayName = $event->date->translatedFormat('l');
                    // Map English to Indonesian if necessary (though translatedFormat should handle it)
                    $calendarEvents->put($dayName, $event);
                }
                }
            }
        }

        return view('siswa.jadwal', compact('user', 'student', 'studentClass', 'schedules', 'calendarEvents'));
    }
}
