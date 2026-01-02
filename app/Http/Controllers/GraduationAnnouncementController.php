<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\GraduationAnnouncement;
use App\Models\Student;
use App\Models\StudentGraduationResult;
use App\Models\Unit;
use Illuminate\Http\Request;

class GraduationAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        /** @var \App\Models\User $user */
        
        $activeYear = AcademicYear::where('status', 'active')->first();
        if (!$activeYear) {
            return back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        // Filter units based on user permissions
        if (in_array($user->role, ['administrator', 'direktur'])) {
            $units = Unit::all();
        } else {
            // For Kurikulum, Headmaster, etc.
            $units = $user->getLearningManagementUnits();
            
            if ($units->isEmpty()) {
                abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk manajemen kelulusan.');
            }
        }

        $selectedUnitId = $request->get('unit_id');
        
        // If not provided or not allowed, default to first available unit
        if (!$selectedUnitId || !$units->contains('id', $selectedUnitId)) {
            $selectedUnitId = $units->first() ? $units->first()->id : null;
        }

        $announcements = GraduationAnnouncement::where('academic_year_id', $activeYear->id)
            ->where('unit_id', $selectedUnitId)
            ->latest()
            ->get();

        return view('graduation.index', compact('announcements', 'units', 'selectedUnitId', 'activeYear'));
    }

    public function show($id)
    {
        $announcement = GraduationAnnouncement::with('results.student.schoolClass')->findOrFail($id);
        
        $user = auth()->user();
        /** @var \App\Models\User $user */
        if (!in_array($user->role, ['administrator', 'direktur'])) {
            if (!$user->isLearningManagerForUnit($announcement->unit_id)) {
                abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk unit ini.');
            }
            $units = $user->getLearningManagementUnits();
        } else {
            $units = Unit::all();
        }

        $selectedUnitId = $announcement->unit_id;
        $activeYear = $announcement->academicYear;
        
        $results = $announcement->results;
        $classes = \App\Models\SchoolClass::where('unit_id', $selectedUnitId)
            ->where('academic_year_id', $announcement->academic_year_id)
            ->orderBy('name')
            ->get();

        return view('graduation.show', compact('announcement', 'units', 'selectedUnitId', 'activeYear', 'results', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'announcement_date' => 'nullable|date',
        ]);

        $user = auth()->user();
        /** @var \App\Models\User $user */
        if (!in_array($user->role, ['administrator', 'direktur'])) {
            if (!$user->isLearningManagerForUnit($request->unit_id)) {
                return back()->with('error', 'Anda tidak memiliki wewenang untuk membuat pengumuman di unit ini.');
            }
        }

        $announcement = GraduationAnnouncement::create([
            'unit_id' => $request->unit_id,
            'academic_year_id' => $request->academic_year_id,
            'title' => $request->title,
            'description' => $request->description,
            'announcement_date' => $request->announcement_date,
            'is_active' => false
        ]);

        return redirect()->route('graduation.show', $announcement->id)->with('success', 'Pengumuman baru berhasil dibuat.');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:graduation_announcements,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'announcement_date' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        $announcement = GraduationAnnouncement::findOrFail($request->id);
        
        $user = auth()->user();
        /** @var \App\Models\User $user */
        if (!in_array($user->role, ['administrator', 'direktur'])) {
            if (!$user->isLearningManagerForUnit($announcement->unit_id)) {
                return back()->with('error', 'Akses Ditolak: Anda tidak memiliki wewenang untuk unit ini.');
            }
        }

        // If activating this one, deactivate others for same unit/year to avoid confusion
        if ($request->has('is_active')) {
            GraduationAnnouncement::where('unit_id', $announcement->unit_id)
                ->where('academic_year_id', $announcement->academic_year_id)
                ->update(['is_active' => false]);
        }

        $announcement->update([
            'title' => $request->title,
            'description' => $request->description,
            'announcement_date' => $request->announcement_date,
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Pengaturan pengumuman berhasil diperbarui.');
    }

    public function storeResult(Request $request)
    {
        $request->validate([
            'graduation_announcement_id' => 'required|exists:graduation_announcements,id',
            'student_id' => 'required|array',
            'student_id.*' => 'exists:students,id',
            'status' => 'required|in:lulus,tidak_lulus,pending',
            'message' => 'nullable|string',
            'skl_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $announcement = GraduationAnnouncement::findOrFail($request->graduation_announcement_id);
        $user = auth()->user();
        /** @var \App\Models\User $user */
        if (!in_array($user->role, ['administrator', 'direktur'])) {
            if (!$user->isLearningManagerForUnit($announcement->unit_id)) {
                return back()->with('error', 'Akses Ditolak: Anda tidak memiliki wewenang untuk unit ini.');
            }
        }

        $sklPath = null;
        if ($request->hasFile('skl_file')) {
            $file = $request->file('skl_file');
            $sklPath = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/skl'), $sklPath);
        }

        foreach ($request->student_id as $studentId) {
            $data = ['status' => $request->status, 'message' => $request->message];
            if ($sklPath) {
                $data['skl_file'] = $sklPath;
            }
            
            StudentGraduationResult::updateOrCreate(
                ['graduation_announcement_id' => $request->graduation_announcement_id, 'student_id' => $studentId],
                $data
            );
        }

        return back()->with('success', count($request->student_id) . ' data siswa berhasil diperbarui.');
    }

    public function downloadSkl($id)
    {
        $result = StudentGraduationResult::findOrFail($id);
        
        if (!$result->skl_file) {
            return back()->with('error', 'File SKL tidak ditemukan.');
        }

        $path = public_path('uploads/skl/' . $result->skl_file);
        
        if (!file_exists($path)) {
            return back()->with('error', 'File fisik tidak ditemukan.');
        }

        return response()->download($path);
    }

    public function deleteResult($id)
    {
        $result = StudentGraduationResult::findOrFail($id);
        $result->delete();
        return back()->with('success', 'Siswa berhasil dihapus dari daftar.');
    }

    public function destroy($id)
    {
        $announcement = GraduationAnnouncement::findOrFail($id);
        
        $user = auth()->user();
        /** @var \App\Models\User $user */
        if (!in_array($user->role, ['administrator', 'direktur'])) {
            if (!$user->isLearningManagerForUnit($announcement->unit_id)) {
                return back()->with('error', 'Akses Ditolak: Anda tidak memiliki wewenang untuk unit ini.');
            }
        }

        $announcement->delete();
        return redirect()->route('graduation.index')->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function getStudentsByClass($classId)
    {
        $students = Student::where('class_id', $classId)
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get(['id', 'nama_lengkap', 'nis']);
            
        return response()->json($students);
    }
}
