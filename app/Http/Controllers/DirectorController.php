<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirectorController extends Controller
{
    public function index()
    {
        // 1. Global Stats
        $stats = [
            'total_students' => Student::where('status', 'aktif')->count(),
            'total_teachers' => User::where('role', 'guru')->where('status', 'aktif')->count(),
            'total_units' => Unit::count(),
            'total_classes' => SchoolClass::count(),
        ];

        // 2. Unit Breakdown
        $units = Unit::withCount([
            'students' => function($q) {
                $q->where('status', 'aktif');
            },
            'classes'
        ])->get();
        
        // Manual Teacher Count per Unit
        // Since teachers are M:N with units via user_jabatan_units
        foreach ($units as $unit) {
            $unit->teacher_count = DB::table('user_jabatan_units')
                ->where('user_jabatan_units.unit_id', $unit->id) // Ambiguous fix
                ->join('users', 'user_jabatan_units.user_id', '=', 'users.id')
                ->where('users.role', 'guru')
                ->where('users.status', 'aktif')
                ->distinct('users.id') // Count unique users, not assignments
                ->count('users.id');
        }

        // 3. Academic Year Context
        $activeYear = AcademicYear::where('status', 'active')->first();

        // 4. Recent Document Requests (Replaces Student Registrations)
        // Get recent submissions pending/validated/approved
        $recentSubmissions = \App\Models\TeacherDocumentSubmission::with(['user.jabatanUnits.unit', 'request'])
            ->orderBy('updated_at', 'desc')
            ->take(6)
            ->get();

        return view('director.index', compact('stats', 'units', 'activeYear', 'recentSubmissions'));
    }
    
    public function employees(Request $request)
    {
        $unitId = $request->get('unit_id');
        $units = Unit::all();
        
        $query = User::whereIn('role', ['guru', 'karyawan', 'staff', 'kepala_sekolah', 'wakil_kurikulum']);
        
        if ($unitId) {
            $query->whereHas('jabatanUnits', function($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            });
        }

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%");
            });
        }
        
        $employees = $query->paginate(10)->withQueryString();
        
        return view('director.employees', compact('employees', 'units', 'unitId'));
    }
}
