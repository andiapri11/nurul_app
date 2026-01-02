<?php

namespace App\Http\Controllers;

use App\Models\TeacherDocumentRequest;
use App\Models\TeacherDocumentSubmission;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CurriculumController extends Controller
{
    // --- Wakasek / Admin Section ---

    public function index(Request $request)
    {
        $user = Auth::user();

        // Get allowed units for the user
        $allowedUnits = $user->getKurikulumUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        // Filter Inputs
        $yearId = $request->get('academic_year_id');
        $unitId = $request->get('unit_id');

        // Base Query
        $query = TeacherDocumentRequest::with('academicYear')
                                       ->orderBy('created_at', 'desc');

        // Apply Access Control (Restrict to allowed units)
        // A request is accessible if:
        // 1. It targets a unit the user manages.
        // 2. OR it targets 'global' (empty target_units) ? Usually strict isolation means NO global.
        //    But if 'target_units' is null/empty, who sees it? Admin? Creator?
        //    Let's assume documents usually have targets.
        //    We filter where `target_units` overlaps with `$allowedUnitIds` OR is created by self.
        
        if (!in_array($user->role, ['administrator', 'direktur'])) {
             $query->where(function($q) use ($allowedUnitIds) {
                 foreach ($allowedUnitIds as $uid) {
                     $q->orWhereJsonContains('target_units', (string)$uid);
                     $q->orWhereJsonContains('target_units', (int)$uid);
                 }
                 // Also show if created by me (even if I lost access later?)
                 $q->orWhere('created_by', Auth::id());
             });
             
             // If user selects a filter unit, verify it is allowed
             if ($unitId && !in_array($unitId, $allowedUnitIds)) {
                 abort(403, 'Anda tidak memiliki akses ke unit ini.');
             }
        }

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }

        if ($unitId) {
            $query->whereJsonContains('target_units', $unitId); 
        }

        $requests = $query->withCount('submissions')->paginate(10)->withQueryString();
        
        // Data for Filters
        $academicYears = AcademicYear::orderBy('status', 'asc')->orderBy('start_year', 'desc')->get();
        // $units = \App\Models\Unit::all(); // OLD
        $units = $allowedUnits; // NEW

        return view('curriculum.index', compact('requests', 'academicYears', 'units', 'yearId', 'unitId'));
    }

    public function jurnalKelas(Request $request)
    {
        $user = Auth::user();
        
        // Get units managing kurikulum OR overall management (allows Principals to see journal)
        $allowedUnits = $user->getKurikulumUnits();
        if ($allowedUnits->isEmpty()) {
            $allowedUnits = $user->getManajemenUnits();
        }
        
        $unitId = $request->get('unit_id');
        $academicYearId = $request->get('academic_year_id');
        $classId = $request->get('class_id');
        $date = $request->get('date', now()->toDateString());

        // Get Academic Years for filter
        $academicYears = AcademicYear::orderBy('status', 'asc')->orderBy('start_year', 'desc')->get();
        
        // Get Classes based on unit and academic year
        $classesQuery = \App\Models\SchoolClass::query();
        if ($unitId) {
            $classesQuery->where('unit_id', $unitId);
        } else {
            $classesQuery->whereIn('unit_id', $allowedUnits->pluck('id'));
        }
        
        // Active Year Fallback
        $activeYear = AcademicYear::where('status', 'active')->first();
        if (!$academicYearId && $activeYear) {
            $academicYearId = $activeYear->id;
        }

        if ($academicYearId) {
            $classesQuery->where('academic_year_id', $academicYearId);
        }
        
        $classes = $classesQuery->orderBy('name')->get();

        // Get Checkins (Jurnal) with complete eager loading
        $checkinsQuery = \App\Models\ClassCheckin::with([
            'schedule.subject', 
            'schedule.schoolClass', 
            'schedule.unit', 
            'user'
        ])
            ->whereDate('checkin_time', $date)
            ->whereHas('schedule') // Ensure data exists
            ->orderBy('checkin_time', 'asc');

        if ($classId) {
            $checkinsQuery->whereHas('schedule', function($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        } elseif ($unitId) {
             $checkinsQuery->whereHas('schedule', function($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            });
        } else {
             $checkinsQuery->whereHas('schedule', function($q) use ($allowedUnits) {
                $q->whereIn('unit_id', $allowedUnits->pluck('id'));
            });
        }

        $checkins = $checkinsQuery->get();

        return view('curriculum.jurnal_kelas', compact(
            'allowedUnits', 'academicYears', 'classes', 
            'unitId', 'academicYearId', 'classId', 'date', 'checkins'
        ));
    }

    public function jurnalKelasPrint(Request $request)
    {
        $user = Auth::user();
        
        // Get units managing kurikulum OR overall management (allows Principals to see journal)
        $allowedUnits = $user->getKurikulumUnits();
        if ($allowedUnits->isEmpty()) {
            $allowedUnits = $user->getManajemenUnits();
        }
        
        $unitId = $request->get('unit_id');
        $academicYearId = $request->get('academic_year_id');
        $classId = $request->get('class_id');
        $date = $request->get('date', now()->toDateString());

        // Get Checkins (Jurnal) with complete eager loading
        $checkinsQuery = \App\Models\ClassCheckin::with([
            'schedule.subject', 
            'schedule.schoolClass', 
            'schedule.unit', 
            'user'
        ])
            ->whereDate('checkin_time', $date)
            ->whereHas('schedule')
            ->orderBy('checkin_time', 'asc');

        if ($classId) {
            $checkinsQuery->whereHas('schedule', function($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        } elseif ($unitId) {
             $checkinsQuery->whereHas('schedule', function($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            });
        } else {
             $checkinsQuery->whereHas('schedule', function($q) use ($allowedUnits) {
                $q->whereIn('unit_id', $allowedUnits->pluck('id'));
            });
        }

        $checkins = $checkinsQuery->get();

        return view('curriculum.jurnal_kelas_print', compact(
            'unitId', 'academicYearId', 'classId', 'date', 'checkins'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        $academicYears = AcademicYear::orderBy('status', 'asc')
                                     ->orderBy('start_year', 'desc')
                                     ->get();
        // $units = \App\Models\Unit::all(); // OLD
        $units = $user->getKurikulumUnits(); // NEW
        
        // Initial empty, populated via AJAX based on Unit
        $subjects = [];
        $grades = [];
                    
        return view('curriculum.create', compact('academicYears', 'units', 'subjects', 'grades'));
    }
    
    // AJAX Grade Fetcher
    public function getGrades(Request $request)
    {
        $query = \App\Models\SchoolClass::distinct()->whereNotNull('grade_code');
        
        // Allow multiple units
        $unitIds = $request->unit_id; 
        if (!empty($unitIds)) {
            // If it's a string comma separated or array
            if (!is_array($unitIds)) $unitIds = explode(',', $unitIds);
            $query->whereIn('unit_id', $unitIds);
        }
        
        $grades = $query->orderBy('grade_code')->pluck('grade_code');
        return response()->json($grades);
    }

    // AJAX Subject Fetcher
    public function getSubjects(Request $request)
    {
        $query = \App\Models\Subject::query();
        
        $unitIds = $request->unit_id;
        if (!empty($unitIds)) {
            if (!is_array($unitIds)) $unitIds = explode(',', $unitIds);
            $query->whereIn('unit_id', $unitIds);
        }
        
        $subjects = $query->orderBy('name')->get(['id', 'name']);
        return response()->json($subjects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|in:ganjil,genap',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
            'target_units' => 'nullable|array',
            'target_subjects' => 'nullable|array',
            'target_grades' => 'nullable|array',
            'target_users' => 'nullable|array',
        ]);

        TeacherDocumentRequest::create([
            'title' => $request->title,
            'description' => $request->description,
            'academic_year_id' => $request->academic_year_id,
            'semester' => $request->semester,
            'due_date' => $request->due_date,
            'created_by' => Auth::id(),
            'target_units' => $request->target_units,
            'target_subjects' => $request->target_subjects,
            'target_grades' => $request->target_grades,
            'target_users' => $request->target_users,
        ]);

        return redirect()->route('curriculum.index')->with('success', 'Permintaan dokumen berhasil dibuat.');
    }

    // AJAX Helper
    public function getTeachers(Request $request)
    {
        // Name Search Mode (Manual Add)
        if ($request->has('name')) {
            $name = $request->name;
            $teachers = User::where('name', 'like', "%{$name}%")
                            ->where('role', 'guru') // Or check jabatan
                            ->limit(20)
                            ->get(['id', 'name']);
            return response()->json($teachers);
        }

        $unitIds = $request->unit_id;
        $subjectIds = $request->subject_id;
        $grades = $request->grade;

        $query = \App\Models\TeachingAssignment::query();

        if (!empty($subjectIds)) {
            if (!is_array($subjectIds)) $subjectIds = explode(',', $subjectIds);
            $query->whereIn('subject_id', $subjectIds);
        }

        if (!empty($unitIds) || !empty($grades)) {
            $query->whereHas('schoolClass', function($q) use ($unitIds, $grades) {
                if (!empty($unitIds)) {
                    if (!is_array($unitIds)) $unitIds = explode(',', $unitIds);
                    $q->whereIn('unit_id', $unitIds);
                }
                if (!empty($grades)) {
                    if (!is_array($grades)) $grades = explode(',', $grades);
                    $q->whereIn('grade_code', $grades);
                }
            });
        }
        
        // Ensure unique users
        $teacherIds = $query->distinct()->pluck('user_id');
        $teachers = User::whereIn('id', $teacherIds)->get(['id', 'name']);

        return response()->json($teachers);
    }

    public function show($id)
    {
        $documentRequest = TeacherDocumentRequest::with(['submissions.user', 'academicYear'])->findOrFail($id);
        
        // Logic to determine "Assigned Teachers"
        $targets = [
            'units' => $documentRequest->target_units,
            'subjects' => $documentRequest->target_subjects,
            'grades' => $documentRequest->target_grades,
            'users' => $documentRequest->target_users,
        ];
        
        $hasSpecificTargets = !empty($targets['units']) || !empty($targets['subjects']) || !empty($targets['grades']) || !empty($targets['users']);

        if ($hasSpecificTargets) {
            // Start with empty collection
             $query = \App\Models\TeachingAssignment::query();
             
             // If User IDs are explicitly targeted, we might just fetch them DIRECTLY.
             // But usually targets work as "User ID OR (Unit AND Subject...)". 
             // However, form logic implied Filters -> Resulting User List. 
             // If `target_users` is populated, it usually overrides others OR is the result of others.
             // If the user manually picked users, `target_users` will have data. 
             // If user picked Only Unit/Subject but didn't pick specific users (left "Global/Semua" in user dropdown),
             // then `target_users` might be empty (depending on form behavior).
             // Based on previous form update `target_users` is "Guru (Otomatis)". It is populated.
             
             // Scenario 1: User explicitly selected teachers in the list.
             if (!empty($targets['users'])) {
                 $teachers = User::whereIn('id', $targets['users'])->where('status', 'aktif')->orderBy('name')->get();
             } else {
                 // Scenario 2: User selected Unit/Subject/Grade but left "Guru" as "Semua" (if possible).
                 // In our form `fetchTeachers` populates the user select.
                 // If the user select allows 'empty' to mean 'all in these criteria':
                 
                 $criteriaQuery = \App\Models\TeachingAssignment::query();
                 $hasCriteria = false;

                 if (!empty($targets['subjects'])) {
                    $criteriaQuery->whereIn('subject_id', $targets['subjects']);
                    $hasCriteria = true;
                 }
                 
                 if (!empty($targets['units']) || !empty($targets['grades'])) {
                    $criteriaQuery->whereHas('schoolClass', function($q) use ($targets) {
                        if (!empty($targets['units'])) $q->whereIn('unit_id', $targets['units']);
                        if (!empty($targets['grades'])) $q->whereIn('grade_code', $targets['grades']);
                    });
                     $hasCriteria = true;
                 }
                 
                 if ($hasCriteria) {
                     $teacherIds = $criteriaQuery->distinct()->pluck('user_id');
                     $teachers = User::whereIn('id', $teacherIds)->where('status', 'aktif')->orderBy('name')->get();
                 } else {
                     // No specific users selected, no specific criteria -> Global
                      $teachers = $this->getAllTeachers();
                 }
             }
        } else {
            // Global Request (No filters saved)
             $teachers = $this->getAllTeachers();
        }

        $submittedUserIds = $documentRequest->submissions->pluck('user_id')->toArray();

        return view('curriculum.show', compact('documentRequest', 'teachers', 'submittedUserIds'));
    }

    private function getAllTeachers() {
        return User::where('role', 'guru')
                ->orWhereHas('jabatans', function($q){
                    $q->where('nama_jabatan', 'like', '%Guru%');
                })
                ->where('status', 'aktif')
                ->orderBy('name')
                ->get();
    }

    public function edit($id)
    {
        $request = TeacherDocumentRequest::findOrFail($id);
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $units = \App\Models\Unit::all();
        return view('curriculum.edit', compact('request', 'academicYears', 'units'));
    }

    public function update(Request $request, $id)
    {
         $docRequest = TeacherDocumentRequest::findOrFail($id);
         $request->validate([
            'title' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|in:ganjil,genap',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
            'target_units' => 'nullable|array',
            'target_subjects' => 'nullable|array',
            'target_grades' => 'nullable|array',
            'target_users' => 'nullable|array',
        ]);

        $docRequest->update([
            'title' => $request->title,
            'description' => $request->description,
            'academic_year_id' => $request->academic_year_id,
            'semester' => $request->semester,
            'due_date' => $request->due_date,
            'target_units' => $request->target_units,
            'target_subjects' => $request->target_subjects,
            'target_grades' => $request->target_grades,
            'target_users' => $request->target_users,
        ]);

        return redirect()->route('curriculum.index')->with('success', 'Permintaan dokumen diperbarui.');
    }

    public function destroy($id)
    {
        $docRequest = TeacherDocumentRequest::findOrFail($id);
        $docRequest->delete();
        return redirect()->route('curriculum.index')->with('success', 'Permintaan dokumen dihapus.');
    }

    // --- Submission Handling ---

    public function updateStatus(Request $request, $submissionId)
    {
        $submission = TeacherDocumentSubmission::findOrFail($submissionId);

        // Prevent modification if already Approved by Principal
        if ($submission->status === 'approved') {
            return redirect()->back()->with('error', 'Dokumen sudah disetujui Kepala Sekolah. Perubahan status tidak diizinkan kecuali dibatalkan oleh Kepala Sekolah.');
        }
        $request->validate([
            'status' => 'required|in:validated,approved,rejected', // Added validated
            'feedback' => 'nullable|string'
        ]);

        $data = [
            'status' => $request->status,
            'feedback' => $request->feedback
        ];

        if ($request->status == 'validated') {
            $data['validated_by'] = Auth::id();
            $data['validated_at'] = now();
        }

        if ($request->status == 'approved') {
            $data['approved_by'] = Auth::id();
            $data['approved_at'] = now();
        }

        $submission->update($data);

        return redirect()->back()->with('success', 'Status submission diperbarui.');
    }
    // --- Teacher Section ---

    public function teacherIndex()
    {
        if (Auth::user()->role === 'direktur') {
            abort(403, 'Akses Ditolak: Direktur tidak memiliki akses Administrasi Guru.');
        }
        // Get active requests
        // Filter by current academic year if needed, or just show all active?
        // Usually administration is per semester.
        // Let's show all Active requests.
        $requests = TeacherDocumentRequest::with('academicYear')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($request) {
                return $request->isTargetFor(Auth::user());
            });
            
        return view('curriculum.teacher_index', compact('requests'));
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip,rar|max:10240',
            'notes' => 'nullable|string|max:500',
        ]);

        $requestId = $id;
        $docRequest = TeacherDocumentRequest::findOrFail($requestId);

        if (!$docRequest->isTargetFor(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk mengupload dokumen ini.');
        }

        // Check Due Date (Strict Deadline: Closed if Today > DueDate)
        if ($docRequest->due_date && $docRequest->due_date < now()->startOfDay()) {
            abort(403, 'Batas waktu pengumpulan dokumen telah berakhir.');
        }
        
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filename = time() . '_' . Auth::id() . '_' . Str::slug($docRequest->title) . '.' . $file->extension();
        
        // Check existing
        $existing = TeacherDocumentSubmission::where('request_id', $requestId)
                        ->where('user_id', Auth::id())
                        ->first();

        if ($existing && $existing->file_path && Storage::disk('public')->exists($existing->file_path)) {
            Storage::disk('public')->delete($existing->file_path);
        }

        $path = $file->storeAs('teacher_documents', $filename, 'public');

        // Determine status: 
        // If Request created by Principal, skip 'pending' and go to 'validated' (Directly to Principal Queue).
        // Otherwise 'pending' (Waiting for Kurikulum).
        $status = 'pending';
        if ($docRequest->creator && $docRequest->creator->isKepalaSekolah()) {
            $status = 'validated';
        }

        TeacherDocumentSubmission::updateOrCreate(
            ['request_id' => $requestId, 'user_id' => Auth::id()],
            [
                'file_path' => $path,
                'original_filename' => $originalName,
                'notes' => $request->notes ?? null,
                'submitted_at' => now(),
                'status' => $status,
                'feedback' => null // Clear feedback
            ]
        );

        return redirect()->back()->with('success', 'Dokumen berhasil diupload.');
    }
}
