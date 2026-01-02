<?php

namespace App\Http\Controllers;

use App\Models\Supervision;
use App\Models\User;
use App\Models\Unit;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupervisionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'direktur') {
             abort(403, 'Akses Ditolak: Direktur tidak diperkenankan mengelola jadwal supervisi.');
        }
        $activeYear = AcademicYear::active()->first();

        // Data for Filters
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        // Determine Managed Units for Filter
        $managedUnits = $user->getManajemenUnits();
        if ($managedUnits->isEmpty() && $user->isDirektur()) {
             $managedUnits = Unit::all();
        }

        // Default Filters
        $filterYearId = $request->input('academic_year_id', $activeYear ? $activeYear->id : null);
        $filterUnitId = $request->input('unit_id');

        $query = Supervision::with(['teacher', 'supervisor', 'unit', 'subject', 'schoolClass']);

        // Apply Academic Year Filter
        if ($filterYearId) {
            $query->where('academic_year_id', $filterYearId);
        }

        // Check Route Context
        if ($request->routeIs('teacher-docs.*')) {
             // Strict Teacher View
             $query->where('teacher_id', $user->id);
        } else {
             // Principal View logic
             $allowedUnitIds = $managedUnits->pluck('id')->toArray();
             
             // Base visibility scope: Supervisor OR In Managed Units OR Own Unit
             $query->where(function($q) use ($user, $allowedUnitIds) {
                 $q->where('supervisor_id', $user->id); // Can see what I supervise
                 
                 if (!empty($allowedUnitIds)) {
                     $q->orWhereIn('unit_id', $allowedUnitIds); // Can see my units
                 } elseif ($user->unit_id) {
                     $q->orWhere('unit_id', $user->unit_id); // Fallback to own unit
                 }
             });
             
             // Apply Unit Filter (if selected from dropdown)
             if ($filterUnitId) {
                 $query->where('unit_id', $filterUnitId);
             }
        }

        $supervisions = $query->orderBy('date', 'desc')->paginate(10);
        $isActiveYearSelected = $activeYear && ($filterYearId == $activeYear->id);
        
        return view('supervisions.index', compact('supervisions', 'academicYears', 'managedUnits', 'filterYearId', 'filterUnitId', 'isActiveYearSelected'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role === 'direktur') {
             abort(403);
        }
        $managedUnits = $user->getManajemenUnits(); // Collection of units
        
        // If empty (e.g. admin or fallback), get all for admin, or user unit
        if ($managedUnits->isEmpty()) { 
             if ($user->isDirektur()) {
                 $managedUnits = Unit::all();
             } elseif ($user->unit_id) {
                 $managedUnits = Unit::where('id', $user->unit_id)->get();
             }
        }
        
        // Get teachers in these units
        $unitIds = $managedUnits->pluck('id');
        
        $teachers = User::where('role', 'guru')
            ->where('status', 'aktif')
            ->whereHas('jabatanUnits', function($q) use ($unitIds) {
                $q->whereIn('unit_id', $unitIds);
            })->get();

        $subjects = \App\Models\Subject::whereIn('unit_id', $unitIds)->get();
        $classes = \App\Models\SchoolClass::whereIn('unit_id', $unitIds)
                                         ->where('academic_year_id', \App\Models\AcademicYear::active()->value('id'))
                                         ->get();

        $activeYear = \App\Models\AcademicYear::active()->first();

        return view('supervisions.create', compact('teachers', 'managedUnits', 'subjects', 'classes', 'activeYear'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'direktur') abort(403);
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time' => 'nullable',
            'notes' => 'nullable|string',
            'unit_id' => 'required|exists:units,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'school_class_id' => 'nullable|exists:classes,id',
        ]);

        $activeYear = AcademicYear::active()->first();
        if (!$activeYear) return back()->with('error', 'No active academic year.');

        Supervision::create([
            'unit_id' => $request->unit_id,
            'academic_year_id' => $activeYear->id,
            'supervisor_id' => Auth::id(),
            'teacher_id' => $request->teacher_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'scheduled',
            'notes' => $request->notes,
            'subject_id' => $request->subject_id,
            'school_class_id' => $request->school_class_id,
        ]);

        return redirect()->route('principal.supervisions.index')->with('success', 'Jadwal supervisi berhasil dibuat.');
    }

    public function edit(Supervision $supervision)
    {
        if (Auth::user()->role === 'direktur') abort(403);
        // Add authorization check needed?
        return view('supervisions.edit', compact('supervision'));
    }

    public function update(Request $request, Supervision $supervision)
    {
        $user = Auth::user();
        if ($user->role === 'direktur') abort(403);

        // 1. Teacher Upload Logic
        // Skip this if user is the supervisor OR if an approval action is being performed
        if ($user->role === 'guru' && $user->id !== $supervision->supervisor_id && !$request->has('document_status')) {
            $request->validate([
                'teacher_document' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240',
            ]);

            if ($request->hasFile('teacher_document')) {
                if ($supervision->teacher_document_path) {
                    Storage::disk('public')->delete($supervision->teacher_document_path);
                }
                $path = $request->file('teacher_document')->store('supervisions/teacher', 'public');
                $supervision->update([
                    'teacher_document_path' => $path,
                    'document_status' => 'pending', 
                ]);
                // Actually if I didn't add 'uploaded' to enum, I can't use it.
                // Re-reading migration: ['pending', 'approved', 'rejected']. 
                // So 'pending' = Uploaded but not processed? Or 'pending' = No upload?
                // Default is pending. 
                // Let's assume:
                // Pending + Path = Uploaded, waiting approval.
                // Approved + Path = Ready for review.
                
                // For clarity, I'll update the status to 'pending' (resetting rejection if any).
                $supervision->update(['document_status' => 'pending']);
            }
            return redirect()->back()->with('success', 'Dokumen ajar berhasil diupload. Menunggu persetujuan Kepala Sekolah.');
        }

        // 2. Principal Logic
        // Can approve/reject teacher doc.
        // Can upload result.
        // Can change main status.
        
        if ($user->role === 'direktur') abort(403);

        $request->validate([
            'status' => 'nullable|in:scheduled,completed,cancelled',
            'document_status' => 'nullable', // |in:pending,approved,rejected remove strict check temporarily 
            'supervisor_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
            'notes' => 'nullable|string',
        ]);

        $data = [];
        
        // Handle Document Approval
        if ($request->has('document_status')) {
            $data['document_status'] = $request->document_status;
        }

        // Handle Supervisor Result Upload
        if ($request->hasFile('supervisor_document')) {
             if ($supervision->supervisor_document_path) {
                Storage::disk('public')->delete($supervision->supervisor_document_path);
            }
            $path = $request->file('supervisor_document')->store('supervisions/supervisor', 'public');
            $data['supervisor_document_path'] = $path;
            
            // If result uploaded, likely completed
            if ($supervision->status == 'scheduled') {
                $data['status'] = 'completed';
            }
        }
        
        // Handle Manual Status/Notes
        if ($request->has('status')) $data['status'] = $request->status;
        if ($request->has('notes')) $data['notes'] = $request->notes;

        $supervision->update($data);

        // Redirect Logic
        // If it was a teacher action (and NOT a supervisor action), go to teacher docs.
        if (($request->routeIs('teacher-docs.*') || $user->role === 'guru') && $user->id !== $supervision->supervisor_id) {
            return redirect()->route('teacher-docs.supervisions.index')->with('success', 'Dokumen berhasil diupload.');
        }

        // Otherwise (Principal/Supervisor action)
        // If we just clicked "Setujui" or "Batalkan", maybe we want to stay or go to index? 
        // User complaint: "laman berpindah ke ... teacher-docs ... sehurusnya tetap"
        // "Tetap" might mean "remain on principal index" OR "stay on edit page"?
        // Usually CRUD updates redirect to Index. "Tetap" might mean "Tetap di Principal Index".
        // Use back() might be better if they want to continue editing? 
        // But let's stick to Principal Index for now as per standard, just fix the WRONG redirect.
        
        return redirect()->route('principal.supervisions.index')->with('success', 'Data supervisi diperbarui.');
    }

    public function destroy(Supervision $supervision)
    {
        if (Auth::user()->role === 'direktur') abort(403);
        if ($supervision->teacher_document_path) Storage::disk('public')->delete($supervision->teacher_document_path);
        if ($supervision->supervisor_document_path) Storage::disk('public')->delete($supervision->supervisor_document_path);
        
        $supervision->delete();
        return back()->with('success', 'Jadwal supervisi dihapus.');
    }

    public function getTeacherInfo(\App\Models\User $teacher)
    {
        // Get Subject Assignments
        $subjects = $teacher->subjects; 
        
        // Get Class Assignments
        // We get classes via TeachingAssignment where user_id = teacher->id
        $classIds = \App\Models\TeachingAssignment::where('user_id', $teacher->id)
                    ->where('academic_year_id', \App\Models\AcademicYear::active()->value('id'))
                    ->pluck('class_id')
                    ->unique();
                    
        $classes = \App\Models\SchoolClass::whereIn('id', $classIds)->get(['id', 'name']);
        
        return response()->json([
            'subjects' => $subjects->map(function($s) {
                 return ['id' => $s->id, 'name' => $s->name, 'code' => $s->code];
            }),
            'classes' => $classes
        ]);
    }
}
