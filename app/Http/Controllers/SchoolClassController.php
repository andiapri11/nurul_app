<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Unit;
use App\Models\User;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function getAllowedUnits()
    {
        $user = auth()->user();
        
        if (in_array($user->role, ['administrator', 'direktur'])) {
            return Unit::all();
        }

        // Get units from Management roles (KS/Kurikulum)
        $units = $user->getLearningManagementUnits();

        // For Guru Role, also include units where they have teaching assignments
        if ($user->role === 'guru') {
            $teachingUnits = $user->getTeachingUnits();
            // Merge collections and remove duplicates
            $units = $units->merge($teachingUnits)->unique('id');
        }

        return $units;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $allowedUnits = $this->getAllowedUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $query = SchoolClass::with(['unit', 'teacher', 'academicYear'])
                    ->withCount('studentHistory')
                    ->whereIn('unit_id', $allowedUnitIds) // Filter by Allowed Units
                    ->latest();

        if ($request->has('unit_id') && $request->unit_id != '') {
             if (!in_array($request->unit_id, $allowedUnitIds)) {
                 abort(403, 'Akses ditolak ke Unit ini.');
             }
             $query->where('unit_id', $request->unit_id);
        }

        // Filter for Teachers (not management)
        if ($user->role === 'guru' && !$user->isManajemenSekolah()) {
            $query->where(function($q) use ($user) {
                // 1. Where they are the class teacher (Wali Kelas)
                $q->where('teacher_id', $user->id)
                  // 2. OR where they have teaching assignments in that class
                  ->orWhereHas('teachingAssignments', function($sq) use ($user) {
                      $sq->where('user_id', $user->id);
                  });
            });
        }

        // By default show active academic year classes, unless specified
        if ($request->has('academic_year_id') && $request->academic_year_id != '') {
            $query->where('academic_year_id', $request->academic_year_id);
        } else {
            $query->forActiveAcademicYear();
        }

        $classes = $query->get();
        $units = $allowedUnits; // Pass allowed units only for filter dropdown
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $activeYear = $academicYears->where('status', 'active')->first();
        
        return view('classes.index', compact('classes', 'units', 'academicYears', 'activeYear'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeYear = AcademicYear::where('status', 'active')->first();
        if (!$activeYear) {
            return redirect()->route('classes.index')->with('error', 'Tidak ada Tahun Ajaran Aktif. Silakan aktifkan tahun ajaran terlebih dahulu.');
        }
        
        $units = $this->getAllowedUnits();
        if ($units->isEmpty()) {
             return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke unit manapun.');
        }

        $teachers = User::where('role', 'guru')->get(); // Ideally filter teachers by allowed units too?
        // But for now, keeping it simple. Maybe filter teachers later if requested.
        
        // Filter students: Active status AND (No Class assigned OR Class assigned is NOT in current active year)
        // AND not in history for this year.
        $students = \App\Models\Student::where('status', 'aktif')
            ->where(function($q) use ($activeYear) {
                // Check current assigned class
                 $q->whereDoesntHave('schoolClass', function($sq) use ($activeYear) {
                     $sq->where('classes.academic_year_id', $activeYear->id);
                 })
                 // Also check history pivot to avoid duplicates if system uses pivot primarily
                 ->whereDoesntHave('classes', function($sq) use ($activeYear) {
                     $sq->where('classes.academic_year_id', $activeYear->id);
                 });
            })
            ->orderBy('nama_lengkap')
            ->get();
        return view('classes.create', compact('units', 'teachers', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'grade_code' => 'nullable|string|max:20', 
            'code' => 'nullable|string|max:20',
            'unit_id' => 'required|exists:units,id',
            'teacher_id' => 'nullable|exists:users,id',
            'student_leader_id' => 'nullable|exists:students,id',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);
        
        if (!in_array($request->unit_id, $allowedIds)) {
            abort(403, 'Anda tidak memiliki akses untuk membuat kelas di unit ini.');
        }

        $activeYear = AcademicYear::where('status', 'active')->first();
        if (!$activeYear) {
            return back()->with('error', 'Gagal: Tidak ada Tahun Ajaran Aktif. Silakan aktifkan tahun ajaran terlebih dahulu.');
        }

        // Check Unique Name in this Academic Year & Unit
        $exists = SchoolClass::where('unit_id', $request->unit_id)
            ->where('academic_year_id', $activeYear->id)
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'Nama Kelas sudah digunakan di Unit dan Tahun Ajaran ini.'])->withInput();
        }

        $data = $request->all();
        $data['academic_year_id'] = $activeYear->id;

        $class = SchoolClass::create($data);

        // Assign selected students to this class
        if ($request->has('student_ids') && is_array($request->student_ids)) {
            $studentIds = $request->student_ids;

            // 1. Class ID column is being phased out, only update History (Pivot)
            // \App\Models\Student::whereIn('id', $studentIds)->update(['class_id' => $class->id]);
            
            // 2. Add to History (Pivot) with Academic Year
            $syncData = [];
            foreach ($studentIds as $id) {
                $syncData[$id] = ['academic_year_id' => $class->academic_year_id];
            }
            $class->studentHistory()->sync($syncData);
        }

        return redirect()->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(SchoolClass $class)
    {
        $user = auth()->user();
        
        // Restriction for Teacher (not management)
        if ($user->role === 'guru' && !$user->isManajemenSekolah()) {
            $isWaliKelas = ($class->teacher_id == $user->id);
            $isTeachingInClass = $class->teachingAssignments()->where('user_id', $user->id)->exists();
            
            if (!$isWaliKelas && !$isTeachingInClass) {
                abort(403, 'Anda tidak memiliki akses ke data kelas ini. Anda hanya dapat melihat data kelas tempat Anda mengajar.');
            }
        }

        return view('classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolClass $class)
    {
        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();
        
        if (!in_array($class->unit_id, $allowedIds)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit kelas ini.');
        }

        // Check if academic year is active
        if ($class->academicYear && $class->academicYear->status !== 'active') {
             return redirect()->route('classes.index')->with('error', 'Hanya kelas di Tahun Ajaran Aktif yang bisa diedit. Silakan aktifkan Tahun Ajaran ini terlebih dahulu di menu Pengaturan.');
        }

        $units = $allowedUnits;
        $teachers = User::where('role', 'guru')->get();

        // Student IDs currently in this class (History/Pivot)
        $currentStudentIds = $class->studentHistory->pluck('id')->toArray();
        
        // Students available (Active status AND (Not in any class of the active year OR already in THIS class))
        $activeYearId = $class->academic_year_id;
        $students = \App\Models\Student::where('status', 'aktif')
            ->where(function($q) use ($class, $activeYearId) {
                 $q->whereIn('id', function($sq) use ($class) {
                     $sq->select('student_id')->from('class_student')->where('class_id', $class->id);
                 })
                 ->orWhereDoesntHave('classes', function($sq) use ($activeYearId) {
                     $sq->where('classes.academic_year_id', $activeYearId);
                 });
            })
            ->orderBy('nama_lengkap')
            ->get();
        return view('classes.edit', compact('class', 'units', 'teachers', 'students', 'currentStudentIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolClass $class)
    {
        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();
        
        if (!in_array($class->unit_id, $allowedIds)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit kelas ini.');
        }

        // Strict Check
        if ($class->academicYear && $class->academicYear->status !== 'active') {
            return back()->with('error', 'Tidak bisa mengubah data kelas dari Tahun Ajaran yang tidak aktif.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'grade_code' => 'nullable|string|max:20',
            'code' => 'nullable|string|max:20',
            'unit_id' => 'required|exists:units,id',
            'teacher_id' => 'nullable|exists:users,id',
            'student_leader_id' => 'nullable|exists:students,id',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);
        
        if (!in_array($request->unit_id, $allowedIds)) { // If trying to change unit
             abort(403, 'Akses ditolak ke unit tujuan.');
        }

        $class->update($request->all());

        // Sync Students Logic
        // Only update student's "class_id" column if this class belongs to an ACTIVE academic year
        $activeYear = AcademicYear::where('status', 'active')->first();
        $isClassInActiveYear = ($activeYear && $class->academic_year_id == $activeYear->id);

        if ($isClassInActiveYear) {
            // Class ID column is being phased out
            // \App\Models\Student::where('class_id', $class->id)->update(['class_id' => null]);
        }

        // 2. Sync to history table (Pivot) with Academic Year
        $studentIds = $request->input('student_ids', []);
        $syncData = [];
        foreach ($studentIds as $id) {
            $syncData[$id] = ['academic_year_id' => $class->academic_year_id];
        }
        $class->studentHistory()->sync($syncData);

        // 3. Class ID column is being phased out
        if ($isClassInActiveYear && !empty($studentIds)) {
            // \App\Models\Student::whereIn('id', $studentIds)->update(['class_id' => $class->id]);
        }

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolClass $class)
    {
        if (auth()->user()->role !== 'administrator') {
            abort(403, 'Akses Ditolak: Hanya Administrator yang diperbolehkan menghapus data kelas.');
        }

        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();
        
        if (!in_array($class->unit_id, $allowedIds)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus kelas ini.');
        }

        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully');
    }

    public function getClassesByUnit($unit_id)
    {
        $allowedUnits = $this->getAllowedUnits();
        if (!$allowedUnits->contains('id', $unit_id)) {
             return response()->json([]); // Or abort(403)
        }
        
        $classes = SchoolClass::where('unit_id', $unit_id)
            ->forActiveAcademicYear()
            ->get();
        return response()->json($classes);
    }

    /**
     * Show the form for mass editing classes.
     */
    public function massEdit(Request $request)
    {
        if (auth()->user()->role !== 'administrator') {
            abort(403, 'Akses Ditolak: Hanya Administrator yang diperbolehkan melakukan update massal data kelas.');
        }

        $allowedUnits = $this->getAllowedUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $activeYear = $academicYears->where('status', 'active')->first();

        if (!$activeYear) {
            return redirect()->route('classes.index')->with('error', 'Tidak ada Tahun Ajaran Aktif.');
        }

        $query = SchoolClass::with(['unit', 'teacher'])
                    ->where('academic_year_id', $activeYear->id)
                    ->whereIn('unit_id', $allowedUnitIds);

        if ($request->has('unit_id') && $request->unit_id != '') {
            $query->where('unit_id', $request->unit_id);
        }

        $classes = $query->orderBy('unit_id')->orderBy('name')->get();
        $teachers = User::whereIn('role', ['guru', 'karyawan', 'staff'])->orderBy('name')->get();

        // Available students (Active and not in any class for this year)
        $availableStudents = \App\Models\Student::where('status', 'aktif')
            ->whereDoesntHave('classes', function($q) use ($activeYear) {
                $q->where('classes.academic_year_id', $activeYear->id);
            })
            ->orderBy('nama_lengkap')
            ->get();

        return view('classes.mass_edit', compact('classes', 'allowedUnits', 'activeYear', 'teachers', 'availableStudents'));
    }

    /**
     * Update multiple classes at once.
     */
    public function massUpdate(Request $request)
    {
        if (auth()->user()->role !== 'administrator') {
            abort(403, 'Akses Ditolak: Hanya Administrator yang diperbolehkan melakukan update massal data kelas.');
        }

        $allowedUnits = $this->getAllowedUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $request->validate([
            'classes' => 'required|array',
            'classes.*.name' => 'required|string|max:255',
            'classes.*.teacher_id' => 'nullable|exists:users,id',
            'classes.*.student_ids' => 'nullable|array',
            'classes.*.student_ids.*' => 'exists:students,id',
        ]);

        $activeYear = AcademicYear::where('status', 'active')->first();

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($request->classes as $id => $data) {
                $class = SchoolClass::findOrFail($id);
                
                // Security check
                if (!in_array($class->unit_id, $allowedUnitIds)) {
                    continue;
                }

                $class->update([
                    'name' => $data['name'],
                    'teacher_id' => $data['teacher_id'] ?: null,
                ]);

                // Sync Students
                $studentIds = $data['student_ids'] ?? [];
                
                // 1. Sync to history table (Pivot)
                $class->studentHistory()->sync($studentIds);

                // 2. Update current class_id for students if active year
                if ($activeYear && $class->academic_year_id == $activeYear->id) {
                    // Reset previous students' class_id for this specific class ONLY
                    \App\Models\Student::where('class_id', $class->id)->update(['class_id' => null]);
                    
                    // Set new ones
                    if (!empty($studentIds)) {
                        \App\Models\Student::whereIn('id', $studentIds)->update(['class_id' => $class->id]);
                    }
                }
            }
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('classes.index')->with('success', 'Berhasil memperbarui data kelas dan pembagian siswa secara massal.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }
}
