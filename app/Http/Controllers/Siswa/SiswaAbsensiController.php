<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SiswaAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $activeYear = AcademicYear::where('status', 'active')->first();
        $academicYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        
        // Fetch all academic years for selection
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        
        // Fetch current month if no month selected
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Get Attendance History for the selected month
        $attendances = StudentAttendance::where('student_id', $student->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        // Summary for current view
        $summary = StudentAttendance::where('student_id', $student->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Overall Summary for active academic year
        $overallSummary = collect();
        if ($academicYearId) {
            $overallSummary = StudentAttendance::where('student_id', $student->id)
                ->where('academic_year_id', $academicYearId)
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
        }

        // Fill missing keys for summary
        $statusKeys = ['present', 'sick', 'permission', 'alpha', 'late', 'school_activity'];
        foreach($statusKeys as $key) {
            if(!isset($summary[$key])) $summary[$key] = 0;
            if(!isset($overallSummary[$key])) $overallSummary[$key] = 0;
        }

        return view('siswa.absensi', compact(
            'user', 'student', 'attendances', 'summary', 'overallSummary', 
            'month', 'year', 'academicYears', 'academicYearId'
        ));
    }
}
