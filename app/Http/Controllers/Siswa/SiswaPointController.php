<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\StudentViolation;
use App\Models\StudentAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaPointController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        // Fetch Active Academic Year
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();

        // Fetch Violations with Academic Year
        $violations = StudentViolation::with('academicYear')
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        // Fetch Achievements with Academic Year
        $achievements = StudentAchievement::with('academicYear')
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        // Grouping logic for Track Record
        $groupedViolations = $violations->groupBy(function($item) {
            return $item->academicYear ? $item->academicYear->name : 'Lainnya';
        });

        $groupedAchievements = $achievements->groupBy(function($item) {
            return $item->academicYear ? $item->academicYear->name : 'Lainnya';
        });

        // Current Year Data
        $currentViolations = $violations->filter(function($v) use ($activeYear) {
            return $activeYear && $v->academic_year_id == $activeYear->id;
        });

        $currentAchievements = $achievements->filter(function($a) use ($activeYear) {
            return $activeYear && $a->academic_year_id == $activeYear->id;
        });

        $totalViolationPoints = $violations->sum('points');
        $currentViolationPoints = $currentViolations->sum('points');

        return view('siswa.point', compact(
            'user', 'student', 'activeYear',
            'groupedViolations', 'groupedAchievements',
            'currentViolations', 'currentAchievements',
            'totalViolationPoints', 'currentViolationPoints'
        ));
    }
}
