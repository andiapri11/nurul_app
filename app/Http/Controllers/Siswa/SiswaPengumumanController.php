<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ClassAnnouncement;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaPengumumanController extends Controller
{
    public function index()
    {
        $user = Auth::guard('student')->user();
        $student = $user->student;

        // Get active academic year
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();

        // Get student's class for current academic year
        $studentClass = $student->classes()
            ->wherePivot('academic_year_id', $activeYear->id ?? 0)
            ->first();

        $announcements = collect();
        if ($studentClass) {
            $announcements = ClassAnnouncement::where('class_id', $studentClass->id)
                ->where('is_active', true)
                ->with('author')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('siswa.pengumuman', compact('announcements', 'studentClass'));
    }
}
