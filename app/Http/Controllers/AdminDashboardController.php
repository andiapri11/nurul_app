<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Unit;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    use \App\Traits\MonitoringTrait;

    public function index(Request $request = null)
    {
        $request = $request ?: request();
        $units = Unit::all();
        $selectedUnitId = $request->get('unit_id', 'all');
        
        $activeYear = AcademicYear::where('status', 'active')->first();
        
        // Use selected unit for monitoring data
        $monitoringData = $this->getMonitoringData($selectedUnitId);

        // Determine scope for stats
        $scopeUnitId = ($selectedUnitId === 'all') ? null : $selectedUnitId;

        // 1. STATISTIK UTAMA (Big Numbers) - Filtered by Unit if selected
        // Requirement: Only active students for the active academic year
        $totalStudents = Student::where('status', 'aktif')
            ->when($activeYear, function($q) use ($activeYear) {
                $q->whereHas('classes', function($sq) use ($activeYear) {
                    $sq->where('class_student.academic_year_id', $activeYear->id);
                });
            })
            ->when($scopeUnitId, function($q) use ($scopeUnitId) {
                $q->where('unit_id', $scopeUnitId);
            })->count();
            
        $totalTeachers = User::whereIn('role', ['guru', 'karyawan', 'staff'])
            ->when($scopeUnitId, function($q) use ($scopeUnitId) {
                $q->whereHas('jabatanUnits', function($sq) use ($scopeUnitId) {
                    $sq->where('unit_id', $scopeUnitId);
                });
            })->count();
        
        // Filter Total Kelas by Active Year & Unit
        $totalClasses = SchoolClass::when($activeYear, function($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })
            ->when($scopeUnitId, function($q) use ($scopeUnitId) {
                $q->where('unit_id', $scopeUnitId);
            })->count();
        
        // 2. PENGAJUAN YANG TELAH DIVALIDASI KEPALA SEKOLAH (5 Terakhir)
        $validatedSubmissions = \App\Models\TeacherDocumentSubmission::with(['user', 'request', 'approver'])
            ->where('status', 'approved')
            ->when($scopeUnitId, function($q) use ($scopeUnitId) {
                $q->whereHas('user', function($sq) use ($scopeUnitId) {
                    $sq->where('unit_id', $scopeUnitId);
                });
            })
            ->latest('approved_at')
            ->take(5)
            ->get();
 
        // 3. STATISTIK PER UNIT (Distribution)
        // Requirement: Active students who have a class in the active year
        $studentsPerUnit = Unit::withCount(['students' => function($q) use ($activeYear) {
                $q->where('status', 'aktif')
                  ->whereHas('classes', function($sq) use ($activeYear) {
                      if ($activeYear) {
                          $sq->where('class_student.academic_year_id', $activeYear->id);
                      }
                  });
            }])
            ->when($scopeUnitId, function($q) use ($scopeUnitId) {
                $q->where('id', $scopeUnitId);
            })->get(); 
        
        // 4. STATISTIK KEPEGAWAIAN
        $activeTeachers = User::whereIn('role', ['guru', 'karyawan', 'staff'])
            ->where('status', 'aktif')
            ->when($scopeUnitId, function($q) use ($scopeUnitId) {
                $q->whereHas('jabatanUnits', function($sq) use ($scopeUnitId) {
                    $sq->where('unit_id', $scopeUnitId);
                });
            })->count();
            
        // 5. QUICK LINKS / SHORTCUTS (Data for view)
        // Kita hardcode di view saja untuk ini.

        return view('admin.dashboard', compact(
            'totalStudents', 
            'totalTeachers', 
            'totalClasses', 
            'activeYear', 
            'validatedSubmissions', 
            'studentsPerUnit',
            'activeTeachers',
            'monitoringData',
            'units',
            'selectedUnitId'
        ));
    }
}
