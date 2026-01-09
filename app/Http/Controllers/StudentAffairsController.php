<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentViolation;
use App\Models\StudentAchievement;
use App\Models\SchoolClass;
use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use App\Models\ExtracurricularReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentAffairsController extends Controller
{
    // ================== VIOLATIONS ==================

    public function indexViolations(Request $request)
    {
        $allowedUnits = Auth::user()->getKesiswaanUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        // If user has no units assigned and not admin/director, show empty or error?
        // Assuming empty list for now.

        // Dropdown Units: Only allowed ones
        $units = $allowedUnits;

        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        
        // Filter Classes based on selected unit/year
        $classesQuery = SchoolClass::query();
        
        if ($request->filled('unit_id')) {
            // Ensure selected unit is allowed
            if (in_array($request->unit_id, $allowedUnitIds)) {
                $classesQuery->where('unit_id', $request->unit_id);
            } else {
                 $classesQuery->whereIn('unit_id', $allowedUnitIds);
            }
        } else {
            $classesQuery->whereIn('unit_id', $allowedUnitIds);
        }

        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $academicYearId = $request->input('academic_year_id', $activeYear ? $activeYear->id : null);

        if ($academicYearId) {
            $classesQuery->where('academic_year_id', $academicYearId);
        }
        $classes = $classesQuery->orderBy('name')->get();

        $query = StudentViolation::with(['student', 'recorder']);
        
        // Enforce Unit Restriction & Academic Year Correlation
        $query->whereHas('student.classes', function($q) use ($allowedUnitIds, $academicYearId) {
            $q->whereIn('classes.unit_id', $allowedUnitIds);
            if ($academicYearId) {
                $q->where('class_student.academic_year_id', $academicYearId);
            }
        });

        // Apply Filters
        if ($request->filled('unit_id') && in_array($request->unit_id, $allowedUnitIds)) {
            $query->whereHas('student.classes', function($q) use ($request, $academicYearId) {
                $q->where('classes.unit_id', $request->unit_id);
                if ($academicYearId) {
                    $q->where('class_student.academic_year_id', $academicYearId);
                }
            });
        }
        
        if ($academicYearId) {
            $query->where('academic_year_id', $academicYearId);
        }
        if ($request->filled('class_id')) {
            $query->whereHas('student.classes', function($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }
        // Clone query for statistics (before status filter)
        $statsQuery = clone $query;
        if ($request->filled('search')) {
            // Include search in stats? Usually yes.
        }

        // Apply Status Filter AFTER cloning for stats
        if ($request->filled('status')) {
            $query->where('follow_up_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
            // Also apply to statsQuery
            $statsQuery->whereHas('student', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('type')) {
            $query->where('violation_type', $request->type);
            $statsQuery->where('violation_type', $request->type);
        }

        $violations = $query->latest('date')->paginate(15);

        // Calculate Stats
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'pending' => (clone $statsQuery)->where('follow_up_status', 'pending')->count(),
            'process' => (clone $statsQuery)->where('follow_up_status', 'process')->count(),
            'done' => (clone $statsQuery)->where('follow_up_status', 'done')->count(),
        ];

        $isViewingActiveYear = $activeYear && ($academicYearId == $activeYear->id);

        return view('student_affairs.violations.index', compact('violations', 'units', 'academicYears', 'classes', 'stats', 'academicYearId', 'isViewingActiveYear'));
    }

    public function exportPdfViolation(Request $request)
    {
        $allowedUnits = Auth::user()->getKesiswaanUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $query = StudentViolation::with(['student.schoolClass', 'recorder']);
        
        // Enforce Unit Restriction
        $query->whereHas('student.schoolClass', function($q) use ($allowedUnitIds) {
            $q->whereIn('classes.unit_id', $allowedUnitIds);
        });

        // Apply Filters
        if ($request->filled('unit_id') && in_array($request->unit_id, $allowedUnitIds)) {
            $query->whereHas('student.schoolClass', function($q) use ($request) {
                $q->where('classes.unit_id', $request->unit_id);
            });
        }
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->filled('class_id')) {
            $query->whereHas('student.classes', function($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }
        if ($request->filled('status')) {
            $query->where('follow_up_status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('violation_type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $violations = $query->latest('date')->get();

        $filterSummary = [
            'unit' => $request->unit_id ? optional(\App\Models\Unit::find($request->unit_id))->name ?? 'Semua' : 'Semua',
            'academic_year' => $request->academic_year_id ? optional(\App\Models\AcademicYear::find($request->academic_year_id))->name ?? 'Semua' : 'Semua',
            'class' => $request->class_id ? optional(SchoolClass::find($request->class_id))->name ?? 'Semua' : 'Semua',
            'status' => $request->status ? ucfirst($request->status) : 'Semua',
        ];

        return view('student_affairs.violations.pdf', compact('violations', 'filterSummary'));
    }

    public function createViolation(Request $request)
    {
        $allowedUnits = Auth::user()->getKesiswaanUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $selectedUnitId = $request->unit_id;
        if ($selectedUnitId && !in_array($selectedUnitId, $allowedUnitIds)) {
            $selectedUnitId = null;
        }

        // Default to first unit if only one and none selected
        if (!$selectedUnitId && count($allowedUnitIds) == 1) {
            $selectedUnitId = $allowedUnitIds[0];
        }

        $selectedClassId = $request->class_id;
        $classes = collect();

        // Start Student Query
        $studentsQuery = Student::where('status', 'aktif')
                    ->with('schoolClass')
                    ->orderBy('nama_lengkap');

        if ($selectedUnitId) {
             // Fetch Classes for the selected unit (Active Year only)
             $classes = SchoolClass::where('unit_id', $selectedUnitId)
                        ->whereHas('academicYear', fn($q) => $q->where('status', 'active'))
                        ->orderBy('name')
                        ->get();

             // Validate Selected Class
             if ($selectedClassId && !$classes->contains('id', $selectedClassId)) {
                 $selectedClassId = null;
             }
             
             if ($selectedClassId) {
                 $studentsQuery->where('class_id', $selectedClassId);
             } else {
                 $studentsQuery->whereHas('schoolClass', function($q) use ($selectedUnitId) {
                    $q->where('unit_id', $selectedUnitId)
                      ->whereHas('academicYear', fn($sq) => $sq->where('status', 'active'));
                 });
             }
        } else {
             // If multiple units and none selected, user requested "Select Unit First".
             // We can return empty students or all allowed.
             // Given the request, filtering simplifies properly.
             $studentsQuery->whereHas('schoolClass', function($q) use ($allowedUnitIds) {
                 $q->whereIn('unit_id', $allowedUnitIds)
                   ->whereHas('academicYear', fn($sq) => $sq->where('status', 'active'));
             });
        }

        $students = $studentsQuery->get();
                    
        return view('student_affairs.violations.create', compact('students', 'allowedUnits', 'selectedUnitId', 'classes', 'selectedClassId'));
    }

    public function storeViolation(Request $request)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();

        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => [
                'exists:students,id',
                function ($attribute, $value, $fail) use ($allowedUnitIds) {
                    $student = Student::with(['schoolClass.academicYear'])->find($value);
                    $sc = $student ? $student->schoolClass->first() : null;
                    if ($student && $sc && !in_array($sc->unit_id, $allowedUnitIds)) {
                         $fail('Anda tidak memiliki akses untuk siswa: ' . $student->nama_lengkap);
                         return;
                    }
                    if ($student && $sc && $sc->academicYear && $sc->academicYear->status !== 'active') {
                        $fail('Siswa ' . $student->nama_lengkap . ' berada di Tahun Pelajaran tidak aktif.');
                    }
                }
            ],
            'date' => 'required|date',
            'violation_type' => 'required|in:Ringan,Sedang,Berat',
            'description' => 'required|string',
            'points' => 'required|integer|min:0',
            'proof' => 'nullable|image|max:2048',
            'follow_up' => 'nullable|string',
        'follow_up_result' => 'nullable|string',
    ]);

    $proofPath = null;
    if ($request->hasFile('proof')) {
        $proofPath = $request->file('proof')->store('violations', 'public');
    }

    foreach ($request->student_ids as $studentId) {
        $followUp = $request->has('need_follow_up') ? $request->follow_up : null;
        $followUpResult = $request->has('need_follow_up') ? $request->follow_up_result : null;
        
        // If result is provided at creation, status becomes 'done' if previously set to pending/process
        // But logic says: if need_follow_up is off, status info is 'done'.
        // If need_follow_up is ON but result is ALREADY there, maybe still 'done' or 'process'?
        // Let's stick to: if result exists, it's 'done'.
        $status = $request->has('need_follow_up') ? ($followUpResult ? 'done' : 'pending') : 'done';

        StudentViolation::create([
            'student_id' => $studentId,
            'date' => $request->date,
            'violation_type' => $request->violation_type,
            'description' => $request->description,
            'points' => $request->points,
            'follow_up' => $followUp,
            'follow_up_result' => $followUpResult,
            'follow_up_status' => $status,
            'proof' => $proofPath,
            'recorded_by' => Auth::id(),
            'academic_year_id' => \App\Models\AcademicYear::where('status', 'active')->first()->id ?? null,
        ]);
    }

        return redirect()->route('student-affairs.violations.index')->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function updateFollowUp(Request $request) 
    {
        $request->validate([
            'violation_id' => 'required|exists:student_violations,id',
            'follow_up' => 'nullable|string',
            'follow_up_result' => 'nullable|string',
            'follow_up_status' => 'required|in:pending,process,done',
            'follow_up_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $violation = StudentViolation::findOrFail($request->violation_id);
        
        // Authorization check
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if(!in_array(optional(optional($violation->student->schoolClass->first())->unit)->id ?? 0, $allowedUnitIds)) {
             abort(403, 'Akses Ditolak');
        }

        // Enforce Active Year (Archive Mode)
        if ($violation->academicYear && $violation->academicYear->status !== 'active') {
             return back()->with('error', 'Data tahun pelajaran tidak aktif diarsipkan dan tidak dapat diperbarui.');
        }

        $data = [
            'follow_up' => $request->follow_up,
            'follow_up_result' => $request->follow_up_result,
            'follow_up_status' => $request->follow_up_status,
        ];

        if ($request->hasFile('follow_up_attachment')) {
            if ($violation->follow_up_attachment) {
                Storage::disk('public')->delete($violation->follow_up_attachment);
            }
            $data['follow_up_attachment'] = $request->file('follow_up_attachment')->store('violations/attachments', 'public');
        }

        $violation->update($data);

        return back()->with('success', 'Data tindak lanjut diperbarui.');
    }

    public function editViolation(StudentViolation $violation)
    {
        // Enforce Access
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if(!in_array(optional(optional($violation->student->schoolClass->first())->unit)->id ?? 0, $allowedUnitIds)) {
             abort(403, 'Akses Ditolak');
        }

        // Enforce Active Year (Archive Mode)
        if ($violation->academicYear && $violation->academicYear->status !== 'active') {
             return redirect()->route('student-affairs.violations.index')
                    ->with('error', 'Data tahun pelajaran tidak aktif diarsipkan dan tidak dapat diedit.');
        }

        // Students in Active Year only
        $students = Student::where('status', 'aktif')
             ->whereHas('schoolClass', function($q) use ($allowedUnitIds) {
                 $q->whereIn('unit_id', $allowedUnitIds)
                   ->whereHas('academicYear', fn($sq) => $sq->where('status', 'active'));
             })
             ->orderBy('nama_lengkap')->get();
             
        return view('student_affairs.violations.edit', compact('violation', 'students'));
    }


    public function updateViolation(Request $request, StudentViolation $violation)
    {
        if ($violation->academicYear && $violation->academicYear->status !== 'active') {
             abort(403, 'Data arsip tidak dapat diubah.');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'violation_type' => 'required|in:Ringan,Sedang,Berat',
            'description' => 'required|string',
            'points' => 'required|integer|min:0',
            'proof' => 'nullable|image|max:2048',
            'follow_up' => 'nullable|string',
            'follow_up_result' => 'nullable|string',
        ]);

        $followUp = $request->has('need_follow_up') ? $request->follow_up : null;
        $followUpResult = $request->has('need_follow_up') ? $request->follow_up_result : null;

        $data = [
            'student_id' => $request->student_id,
            'date' => $request->date,
            'violation_type' => $request->violation_type,
            'description' => $request->description,
            'points' => $request->points,
            'follow_up' => $followUp,
            'follow_up_result' => $followUpResult,
        ];

        if (!$request->has('need_follow_up')) {
            $data['follow_up_status'] = 'done';
        }

        if ($request->hasFile('proof')) {
            // Delete old proof
            if ($violation->proof) {
                Storage::disk('public')->delete($violation->proof);
            }
            $data['proof'] = $request->file('proof')->store('violations', 'public');
        }

        $violation->update($data);

        return redirect()->route('student-affairs.violations.index')->with('success', 'Data pelanggaran diperbarui.');
    }

    public function destroyViolation(StudentViolation $violation)
    {
        if ($violation->academicYear && $violation->academicYear->status !== 'active') {
             return back()->with('error', 'Data arsip tidak dapat dihapus.');
        }

        if ($violation->proof) {
            Storage::disk('public')->delete($violation->proof);
        }
        $violation->delete();
        return redirect()->route('student-affairs.violations.index')->with('success', 'Data pelanggaran dihapus.');
    }

    // ================== ACHIEVEMENTS ==================

    public function indexAchievements(Request $request)
    {
        $allowedUnits = Auth::user()->getKesiswaanUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        // Dropdown Units: Only allowed ones
        $units = $allowedUnits;

        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        
        // Filter Classes based on selected unit/year
        $classesQuery = SchoolClass::query();
        if ($request->filled('unit_id')) {
            if (in_array($request->unit_id, $allowedUnitIds)) {
                $classesQuery->where('unit_id', $request->unit_id);
            } else {
                 $classesQuery->whereIn('unit_id', $allowedUnitIds);
            }
        } else {
            $classesQuery->whereIn('unit_id', $allowedUnitIds);
        }

        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $academicYearId = $request->input('academic_year_id', $activeYear ? $activeYear->id : null);

        if ($academicYearId) {
            $classesQuery->where('academic_year_id', $academicYearId);
        }
        $classes = $classesQuery->orderBy('name')->get();

        $query = StudentAchievement::with(['student', 'recorder']);
        
        // Enforce Unit Restriction & Academic Year Correlation
        $query->whereHas('student.classes', function($q) use ($allowedUnitIds, $academicYearId) {
            $q->whereIn('classes.unit_id', $allowedUnitIds);
            if ($academicYearId) {
                $q->where('class_student.academic_year_id', $academicYearId);
            }
        });

        // Apply Filters
        if ($request->filled('unit_id') && in_array($request->unit_id, $allowedUnitIds)) {
            $query->whereHas('student.classes', function($q) use ($request, $academicYearId) {
                $q->where('classes.unit_id', $request->unit_id);
                if ($academicYearId) {
                    $q->where('class_student.academic_year_id', $academicYearId);
                }
            });
        }
        
        if ($academicYearId) {
            $query->where('academic_year_id', $academicYearId);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student.classes', function($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }

        // Clone for Stats
        $statsQuery = clone $query;

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
            $statsQuery->whereHas('student', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $achievements = $query->latest('date')->paginate(15);

        // Calculate Stats
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'nasional' => (clone $statsQuery)->whereIn('level', ['Nasional', 'Internasional'])->count(),
            'provinsi' => (clone $statsQuery)->where('level', 'Provinsi')->count(),
            'kabupaten' => (clone $statsQuery)->where('level', 'Kabupaten/Kota')->count(),
        ];

        $isViewingActiveYear = $activeYear && ($academicYearId == $activeYear->id);

        return view('student_affairs.achievements.index', compact('achievements', 'units', 'academicYears', 'classes', 'stats', 'academicYearId', 'isViewingActiveYear'));
    }

    public function exportPdfAchievement(Request $request)
    {
        $allowedUnits = Auth::user()->getKesiswaanUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $query = StudentAchievement::with(['student.schoolClass', 'recorder']);
        
        // Enforce Unit Restriction
        $query->whereHas('student.schoolClass', function($q) use ($allowedUnitIds) {
            $q->whereIn('classes.unit_id', $allowedUnitIds);
        });

        // Apply Filters
        if ($request->filled('unit_id') && in_array($request->unit_id, $allowedUnitIds)) {
            $query->whereHas('student.schoolClass', function($q) use ($request) {
                $q->where('classes.unit_id', $request->unit_id);
            });
        }
        
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student.classes', function($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $achievements = $query->latest('date')->get();

        $filterSummary = [
            'unit' => $request->unit_id ? optional(\App\Models\Unit::find($request->unit_id))->name ?? 'Semua' : 'Semua',
            'academic_year' => $request->academic_year_id ? optional(\App\Models\AcademicYear::find($request->academic_year_id))->name ?? 'Semua' : 'Semua',
            'class' => $request->class_id ? optional(\App\Models\SchoolClass::find($request->class_id))->name ?? 'Semua' : 'Semua',
            'level' => $request->level ?: 'Semua',
        ];

        return view('student_affairs.achievements.pdf', compact('achievements', 'filterSummary'));
    }

    public function createAchievement(Request $request)
    {
        $allowedUnits = Auth::user()->getKesiswaanUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $selectedUnitId = $request->unit_id;
        if ($selectedUnitId && !in_array($selectedUnitId, $allowedUnitIds)) {
            $selectedUnitId = null;
        }

        // Default to first unit if only one and none selected
        if (!$selectedUnitId && count($allowedUnitIds) == 1) {
            $selectedUnitId = $allowedUnitIds[0];
        }

        $selectedClassId = $request->class_id;
        $classes = collect();

        // Start Student Query
        $studentsQuery = Student::where('status', 'aktif')
                    ->with('schoolClass')
                    ->orderBy('nama_lengkap');

        if ($selectedUnitId) {
             // Fetch Classes for the selected unit (Active Year only)
             $classes = \App\Models\SchoolClass::where('unit_id', $selectedUnitId)
                        ->whereHas('academicYear', fn($q) => $q->where('status', 'active'))
                        ->orderBy('name')
                        ->get();

             // Validate Selected Class
             if ($selectedClassId && !$classes->contains('id', $selectedClassId)) {
                 $selectedClassId = null;
             }
             
             if ($selectedClassId) {
                 $studentsQuery->where('class_id', $selectedClassId);
             } else {
                 $studentsQuery->whereHas('schoolClass', function($q) use ($selectedUnitId) {
                    $q->where('unit_id', $selectedUnitId)
                      ->whereHas('academicYear', fn($sq) => $sq->where('status', 'active'));
                 });
             }
        } else {
             $studentsQuery->whereHas('schoolClass', function($q) use ($allowedUnitIds) {
                 $q->whereIn('unit_id', $allowedUnitIds)
                   ->whereHas('academicYear', fn($sq) => $sq->where('status', 'active'));
             });
        }

        $students = $studentsQuery->get();
                    
        return view('student_affairs.achievements.create', compact('students', 'allowedUnits', 'selectedUnitId', 'classes', 'selectedClassId'));
    }

    public function storeAchievement(Request $request)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();

        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => [
                'exists:students,id',
                function ($attribute, $value, $fail) use ($allowedUnitIds) {
                    $student = Student::with(['schoolClass.academicYear'])->find($value);
                    $sc = $student ? $student->schoolClass->first() : null;
                    if ($student && $sc && !in_array($sc->unit_id, $allowedUnitIds)) {
                         $fail('Anda tidak memiliki akses untuk siswa: ' . $student->nama_lengkap);
                         return;
                    }
                    if ($student && $sc && $sc->academicYear && $sc->academicYear->status !== 'active') {
                        $fail('Siswa ' . $student->nama_lengkap . ' berada di Tahun Pelajaran tidak aktif.');
                    }
                }
            ],
            'date' => 'required|date',
            'achievement_name' => 'required|string',
            'level' => 'required|string',
            'proof' => 'nullable|image|max:2048',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('achievements', 'public');
        }

        foreach ($request->student_ids as $studentId) {
            StudentAchievement::create([
                'student_id' => $studentId,
                'date' => $request->date,
                'achievement_name' => $request->achievement_name,
                'level' => $request->level,
                'proof' => $proofPath,
                'recorded_by' => Auth::id(),
                'academic_year_id' => \App\Models\AcademicYear::where('status', 'active')->first()->id ?? null,
            ]);
        }

        return redirect()->route('student-affairs.achievements.index')->with('success', 'Prestasi berhasil dicatat.');
    }

    public function editAchievement(StudentAchievement $achievement)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        
        if(!in_array(optional(optional($achievement->student->schoolClass->first())->unit)->id ?? 0, $allowedUnitIds)) {
             abort(403, 'Akses Ditolak');
        }

        // Archive Check
        if ($achievement->academicYear && $achievement->academicYear->status !== 'active') {
             return redirect()->route('student-affairs.achievements.index')
                   ->with('error', 'Data tahun pelajaran tidak aktif diarsipkan dan tidak dapat diedit.');
        }

        $students = Student::where('status', 'aktif')
            ->whereHas('schoolClass', function($q) use ($allowedUnitIds) {
                $q->whereIn('unit_id', $allowedUnitIds)
                  ->whereHas('academicYear', fn($sq) => $sq->where('status', 'active'));
            })
            ->orderBy('nama_lengkap')->get();
            
        return view('student_affairs.achievements.edit', compact('achievement', 'students'));
    }

    public function updateAchievement(Request $request, StudentAchievement $achievement)
    {
        if ($achievement->academicYear && $achievement->academicYear->status !== 'active') {
            abort(403, 'Data arsip tidak dapat diubah.');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'achievement_name' => 'required|string',
            'level' => 'required|string',
            'proof' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('proof');

        if ($request->hasFile('proof')) {
            // Delete old proof
            if ($achievement->proof) {
                Storage::disk('public')->delete($achievement->proof);
            }
            $data['proof'] = $request->file('proof')->store('achievements', 'public');
        }

        $achievement->update($data);

        return redirect()->route('student-affairs.achievements.index')->with('success', 'Data prestasi diperbarui.');
    }

    public function destroyAchievement(StudentAchievement $achievement)
    {
        if ($achievement->academicYear && $achievement->academicYear->status !== 'active') {
             return back()->with('error', 'Data arsip tidak dapat dihapus.');
        }

        if ($achievement->proof) {
            Storage::disk('public')->delete($achievement->proof);
        }
        $achievement->delete();
        return redirect()->route('student-affairs.achievements.index')->with('success', 'Data prestasi dihapus.');
    }

    // ================== BLACK BOOK ==================

    public function blackBook(Request $request) 
    {
        $allowedUnits = Auth::user()->getKesiswaanUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $search = $request->get('search');
        $unitId = $request->get('unit_id');
        $academicYearId = $request->get('academic_year_id');
        $classId = $request->get('class_id');

        $query = Student::with(['schoolClass.unit'])
            ->withSum('violations', 'points')
            ->whereHas('schoolClass', function($q) use ($allowedUnitIds) {
                $q->whereIn('unit_id', $allowedUnitIds);
            })
            ->where('status', 'aktif');
            
        // Dynamic Threshold Check Logic:
        // Compare Total Violation Points vs Unit's Threshold
        $thresholdSql = "(select `black_book_points` from `units` 
                         inner join `classes` on `units`.`id` = `classes`.`unit_id` 
                         inner join `class_student` on `classes`.`id` = `class_student`.`class_id` 
                         where `class_student`.`student_id` = `students`.`id` 
                         AND `classes`.`academic_year_id` = (select id from academic_years where status = 'active' limit 1)
                         limit 1)";
        
        $query->whereRaw("(select coalesce(sum(points), 0) from `student_violations` where `student_violations`.`student_id` = `students`.`id`) > IFNULL($thresholdSql, 10)");
        
        // Filter Unit
        if ($unitId && in_array($unitId, $allowedUnitIds)) {
            $query->whereHas('schoolClass', function ($q) use ($unitId) {
                $q->where('unit_id', $unitId); 
            });
        }

        // Filter Academic Year
        if ($academicYearId) {
             $query->whereHas('classes', function ($q) use ($academicYearId) {
                $q->where('class_student.academic_year_id', $academicYearId); 
            });
        }

        // Filter Class
        if ($classId) {
            $query->whereHas('classes', function($q) use ($classId) {
                $q->where('classes.id', $classId);
            });
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $students = $query->orderByDesc('violations_sum_points')->paginate(20);

        // Data for Filters
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        
        $classes = collect();
        if ($unitId) {
            $classesQuery = \App\Models\SchoolClass::where('unit_id', $unitId);
            if ($academicYearId) {
                $classesQuery->where('academic_year_id', $academicYearId);
            }
            $classes = $classesQuery->orderBy('name')->get();
        }

        // Get selected unit for settings display
        $selectedUnit = null;
        if ($unitId) {
            $selectedUnit = \App\Models\Unit::find($unitId);
        }

        return view('student_affairs.black_book.index', compact('students', 'allowedUnits', 'selectedUnit', 'academicYears', 'classes'));
    }

    public function updateBlackBookThreshold(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'points' => 'required|integer|min:1'
        ]);

        $unit = \App\Models\Unit::findOrFail($request->unit_id);
        
        // Authorization Check
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if (!in_array($unit->id, $allowedUnitIds)) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        $unit->update(['black_book_points' => $request->points]);

        return redirect()->route('student-affairs.black-book', ['unit_id' => $unit->id])
            ->with('success', 'Batas poin buku hitam untuk unit ' . $unit->name . ' diperbarui menjadi ' . $request->points);
    }

    // ================== EXTRACURRICULARS ==================

    public function indexExtracurriculars(Request $request)
    {
        $allowedUnits = Auth::user()->getKesiswaanUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $academicYearId = $request->input('academic_year_id', $activeYear ? $activeYear->id : null);

        $query = Extracurricular::with(['unit'])->whereIn('unit_id', $allowedUnitIds);

        if ($academicYearId) {
            $query->where('academic_year_id', $academicYearId);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $extracurriculars = $query->orderBy('name')->paginate(15);
        
        // Add member count for selected year to each extra
        foreach($extracurriculars as $extra) {
            $extra->member_count = $extra->members()->where('academic_year_id', $academicYearId)->count();
        }

        $units = $allowedUnits;
        $isViewingActiveYear = $activeYear && ($academicYearId == $activeYear->id);

        return view('student_affairs.extracurriculars.index', compact('extracurriculars', 'units', 'academicYears', 'academicYearId', 'isViewingActiveYear'));
    }

    public function storeExtracurricular(Request $request)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();

        $request->validate([
            'unit_id' => 'required|in:' . implode(',', $allowedUnitIds),
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'coach_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $academicYear = \App\Models\AcademicYear::find($request->academic_year_id);
        if (!$academicYear || $academicYear->status !== 'active') {
            return back()->with('error', 'Ekstrakurikuler baru hanya dapat dibuat pada Tahun Pelajaran Aktif.');
        }

        Extracurricular::create($request->all());

        return back()->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function updateExtracurricular(Request $request, Extracurricular $extracurricular)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if (!in_array($extracurricular->unit_id, $allowedUnitIds)) {
            abort(403);
        }

        $request->validate([
            'unit_id' => 'required|in:' . implode(',', $allowedUnitIds),
            'name' => 'required|string|max:255',
            'coach_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $extracurricular->update($request->all());

        return back()->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroyExtracurricular(Extracurricular $extracurricular)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if (!in_array($extracurricular->unit_id, $allowedUnitIds)) {
            abort(403);
        }

        $extracurricular->delete();

        return back()->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }

    public function manageExtracurricularMembers(Request $request, Extracurricular $extracurricular)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if (!in_array($extracurricular->unit_id, $allowedUnitIds)) {
            abort(403);
        }

        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $academicYearId = $request->input('academic_year_id', $activeYear ? $activeYear->id : null);
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();

        $membersQuery = ExtracurricularMember::with(['student.schoolClass'])
            ->where('extracurricular_id', $extracurricular->id);
        
        if ($academicYearId) {
            $membersQuery->where('academic_year_id', $academicYearId);
        }

        $members = $membersQuery->get();

        // Allowed units for cross-unit enrollment
        $allowedUnits = Auth::user()->getKesiswaanUnits();
        $selectedUnitId = $request->input('unit_id', $extracurricular->unit_id);

        // Classes for filtering students based on selected unit
        $classes = \App\Models\SchoolClass::where('unit_id', $selectedUnitId)
            ->where('academic_year_id', $academicYearId)
            ->orderBy('name')
            ->get();

        $selectedClassId = $request->input('class_id');

        // Students available to join (Filtered by Unit and optionally Class)
        $memberStudentIds = $members->pluck('student_id')->toArray();
        $studentsQuery = Student::where('status', 'aktif')
            ->whereHas('schoolClass', function($q) use ($selectedUnitId, $academicYearId, $selectedClassId) {
                $q->where('unit_id', $selectedUnitId);
                if ($academicYearId) $q->where('academic_year_id', $academicYearId);
                if ($selectedClassId) $q->where('id', $selectedClassId);
            })
            ->whereNotIn('id', $memberStudentIds);
        
        $students = $studentsQuery->orderBy('nama_lengkap')->get();

        $isViewingActiveYear = $activeYear && ($academicYearId == $activeYear->id);

        return view('student_affairs.extracurriculars.members', compact('extracurricular', 'members', 'students', 'academicYears', 'academicYearId', 'isViewingActiveYear', 'classes', 'selectedClassId', 'allowedUnits', 'selectedUnitId'));
    }

    public function addExtracurricularMember(Request $request, Extracurricular $extracurricular)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if (!in_array($extracurricular->unit_id, $allowedUnitIds)) {
            abort(403);
        }

        $request->validate([
            'student_ids' => 'required_without:add_all|array',
            'student_ids.*' => 'exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'role' => 'required|string|max:255',
            'add_all' => 'nullable|boolean'
        ]);

        $academicYear = \App\Models\AcademicYear::find($request->academic_year_id);
        if (!$academicYear || $academicYear->status !== 'active') {
            return back()->with('error', 'Anggota hanya dapat ditambahkan pada Tahun Pelajaran Aktif.');
        }

        if ($request->input('add_all') == '1') {
            $selectedUnitId = $request->input('unit_id', $extracurricular->unit_id);
            $selectedClassId = $request->input('class_id');
            $activeYearId = $academicYear->id;

            // Find students matching filters who are NOT yet members
            $studentIds = Student::where('status', 'aktif')
                ->whereHas('schoolClass', function($q) use ($selectedUnitId, $activeYearId, $selectedClassId) {
                    $q->where('unit_id', $selectedUnitId);
                    $q->where('academic_year_id', $activeYearId);
                    if ($selectedClassId) $q->where('id', $selectedClassId);
                })
                ->whereDoesntHave('extracurriculars', function($q) use ($extracurricular, $activeYearId) {
                    $q->where('extracurricular_id', $extracurricular->id)
                      ->where('academic_year_id', $activeYearId);
                })
                ->pluck('id')
                ->toArray();
        } else {
            // Single Addition Mode
            $studentIds = $request->student_ids;
            if (is_string($studentIds)) $studentIds = explode(',', $studentIds);
            if (!is_array($studentIds)) $studentIds = (array)$studentIds;
        }

        if (empty($studentIds)) {
            return back()->with('info', 'Tidak ada siswa tambahan yang ditemukan atau dipilih.');
        }

        $newCount = 0;
        foreach ($studentIds as $studentId) {
            if (!$studentId) continue;
            
            $member = ExtracurricularMember::firstOrCreate([
                'extracurricular_id' => $extracurricular->id,
                'student_id' => $studentId,
                'academic_year_id' => $academicYear->id,
            ], [
                'role' => $request->role ?? 'Anggota',
            ]);

            if ($member->wasRecentlyCreated) {
                $newCount++;
            }
        }

        return back()->with('success', "Berhasil menambahkan $newCount siswa ke daftar anggota.");
    }

    public function removeExtracurricularMember(ExtracurricularMember $member)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if (!in_array($member->extracurricular->unit_id, $allowedUnitIds)) {
            abort(403);
        }

        if ($member->academicYear->status !== 'active') {
             return back()->with('error', 'Oops! Data di tahun pelajaran yang diarsipkan tidak dapat dihapus.');
        }

        $member->delete();

        return back()->with('success', 'Anggota berhasil dihapus.');
    }

    // ================== ACHIEVEMENTS & REPORTS ==================

    public function extracurricularAchievements(Request $request, Extracurricular $extracurricular)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if (!in_array($extracurricular->unit_id, $allowedUnitIds)) {
            abort(403);
        }

        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        $activeYear = $academicYears->where('status', 'active')->first();
        $academicYearId = $request->input('academic_year_id', $activeYear ? $activeYear->id : null);

        // Members for this specific academic year
        $members = ExtracurricularMember::with('student.schoolClass')
            ->where('extracurricular_id', $extracurricular->id)
            ->where('academic_year_id', $academicYearId)
            ->get()
            ->sortBy('student.nama_lengkap');

        // Reports for this extracurricular and academic year
        $reports = ExtracurricularReport::where('extracurricular_id', $extracurricular->id)
            ->where('academic_year_id', $academicYearId)
            ->latest()
            ->get();

        $isViewingActiveYear = $activeYear && ($academicYearId == $activeYear->id);

        return view('student_affairs.extracurriculars.achievements', compact(
            'extracurricular', 'members', 'reports', 'academicYears', 'academicYearId', 'isViewingActiveYear'
        ));
    }

    public function updateExtracurricularAchievements(Request $request, Extracurricular $extracurricular)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if (!in_array($extracurricular->unit_id, $allowedUnitIds)) {
            abort(403);
        }

        $request->validate([
            'achievements' => 'required|array',
            'achievements.*.grade_ganjil' => 'nullable|string|max:10',
            'achievements.*.description_ganjil' => 'nullable|string',
            'achievements.*.grade_genap' => 'nullable|string|max:10',
            'achievements.*.description_genap' => 'nullable|string',
        ]);

        foreach ($request->achievements as $id => $data) {
            $member = ExtracurricularMember::where('id', $id)
                ->where('extracurricular_id', $extracurricular->id)
                ->first();
            
            if ($member) {
                if ($member->academicYear->status !== 'active') continue;
                
                $member->update([
                    'grade_ganjil' => $data['grade_ganjil'],
                    'description_ganjil' => $data['description_ganjil'],
                    'grade_genap' => $data['grade_genap'],
                    'description_genap' => $data['description_genap'],
                ]);
            }
        }

        return back()->with('success', 'Nilai capaian berhasil diperbarui.');
    }

    public function storeExtracurricularReport(Request $request, Extracurricular $extracurricular)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if (!in_array($extracurricular->unit_id, $allowedUnitIds)) {
            abort(403);
        }

        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'description' => 'nullable|string',
        ]);

        $academicYear = \App\Models\AcademicYear::find($request->academic_year_id);
        if ($academicYear->status !== 'active') {
            return back()->with('error', 'Laporan hanya dapat diunggah pada Tahun Pelajaran Aktif.');
        }

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('extracurricular_reports', 'public');
            
            ExtracurricularReport::create([
                'extracurricular_id' => $extracurricular->id,
                'academic_year_id' => $request->academic_year_id,
                'title' => $request->title,
                'file_path' => $path,
                'description' => $request->description,
            ]);
        }

        return back()->with('success', 'Laporan kegiatan berhasil diunggah.');
    }

    public function deleteExtracurricularReport(ExtracurricularReport $report)
    {
        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if (!in_array($report->extracurricular->unit_id, $allowedUnitIds)) {
            abort(403);
        }

        // Delete file
        if ($report->file_path) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        return back()->with('success', 'Laporan kegiatan berhasil dihapus.');
    }
    // ================== ATTENDANCE SETTINGS ==================

    public function attendanceSettings(Request $request)
    {
        $units = Auth::user()->getKesiswaanUnits();
        return view('student_affairs.attendance.settings', compact('units'));
    }

    public function updateAttendanceSettings(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'attendance_start' => 'nullable|date_format:H:i',
            'attendance_end' => 'nullable|date_format:H:i',
        ]);

        $allowedUnitIds = Auth::user()->getKesiswaanUnits()->pluck('id')->toArray();
        if (!in_array($request->unit_id, $allowedUnitIds)) {
            abort(403);
        }

        $unit = \App\Models\Unit::findOrFail($request->unit_id);
        $unit->update([
            'attendance_start' => $request->attendance_start,
            'attendance_end' => $request->attendance_end,
        ]);

        return back()->with('success', 'Pengaturan batas waktu absen berhasil diperbarui.');
    }

    // ================== ATTENDANCE DATA ==================

    public function attendanceData(Request $request)
    {
        $allowedUnits = Auth::user()->getKesiswaanUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $academicYearId = $request->input('academic_year_id', $activeYear ? $activeYear->id : null);
        $date = $request->input('date', now()->format('Y-m-d'));

        $units = $allowedUnits;
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();

        $classesQuery = SchoolClass::query();
        if ($request->filled('unit_id')) {
            if (in_array($request->unit_id, $allowedUnitIds)) {
                $classesQuery->where('unit_id', $request->unit_id);
            } else {
                $classesQuery->whereIn('unit_id', $allowedUnitIds);
            }
        } else {
            $classesQuery->whereIn('unit_id', $allowedUnitIds);
        }

        if ($academicYearId) {
            $classesQuery->where('academic_year_id', $academicYearId);
        }
        $classes = $classesQuery->orderBy('name')->get();

        $attendanceQuery = \App\Models\StudentAttendance::with(['student', 'schoolClass', 'academicYear'])
            ->whereHas('schoolClass', function($q) use ($allowedUnitIds) {
                $q->whereIn('unit_id', $allowedUnitIds);
            });

        if ($request->filled('unit_id') && in_array($request->unit_id, $allowedUnitIds)) {
            $attendanceQuery->whereHas('schoolClass', function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        if ($request->filled('class_id')) {
            $attendanceQuery->where('class_id', $request->class_id);
        }

        if ($academicYearId) {
            $attendanceQuery->where('academic_year_id', $academicYearId);
        }

        $attendanceQuery->where('date', $date);

        $attendances = $attendanceQuery->get();

        return view('student_affairs.attendance.index', compact('attendances', 'units', 'academicYears', 'classes', 'academicYearId', 'date'));
    }
}
