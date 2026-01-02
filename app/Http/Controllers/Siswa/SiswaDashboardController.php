<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->guard('student')->user();
        if ($user) {
            $user->load('student.unit');
        }
        $schedules = collect();
        $studentClass = null;

        // Fetch Active Academic Year
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        
        $graduationResult = null;
        $graduationAnnouncement = null;

        if ($user && $user->student) {
            $student = $user->student;
            
            // Try to find the student's class for the active academic year from history
            if ($activeYear) {
                $studentClass = $student->classes()->wherePivot('academic_year_id', $activeYear->id)->first();
            }
            
            // Graduation Logic
            if ($activeYear) {
                $graduationAnnouncement = \App\Models\GraduationAnnouncement::where('academic_year_id', $activeYear->id)
                    ->where('unit_id', $student->unit_id)
                    ->where('is_active', true)
                    ->first();
                
                if ($graduationAnnouncement) {
                    $graduationResult = \App\Models\StudentGraduationResult::where('student_id', $student->id)
                        ->where('graduation_announcement_id', $graduationAnnouncement->id)
                        ->first();
                }
            }

            // Validation: Only show schedule if the student's class belongs to the active academic year
            if ($activeYear && $studentClass && $studentClass->academic_year_id == $activeYear->id) {
                $classId = $studentClass->id;
                
                $schedules = \App\Models\Schedule::with(['subject', 'teacher'])
                    ->where('class_id', $classId)
                    ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                    ->orderBy('start_time')
                    ->get()
                    ->groupBy('day');

                // Fetch Announcements for the current active class
                $newAnnouncementsCount = \App\Models\ClassAnnouncement::where('class_id', $classId)
                    ->where('is_active', true)
                    ->where('created_at', '>=', now()->subDays(3))
                    ->count();
                
                $latestAnnouncement = \App\Models\ClassAnnouncement::where('class_id', $classId)
                    ->where('is_active', true)
                    ->orderByDesc('created_at')
                    ->first();
            } else {
                $newAnnouncementsCount = 0;
                $latestAnnouncement = null;
            }
        } else {
            $newAnnouncementsCount = 0;
            $latestAnnouncement = null;
        }

        return view('siswa.dashboard', compact(
            'schedules', 'user', 'studentClass', 
            'graduationResult', 'graduationAnnouncement',
            'newAnnouncementsCount', 'latestAnnouncement'
        ));
    }
}
