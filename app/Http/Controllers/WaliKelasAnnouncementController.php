<?php

namespace App\Http\Controllers;

use App\Models\ClassAnnouncement;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaliKelasAnnouncementController extends Controller
{
    private function getMyClass()
    {
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        
        $query = SchoolClass::where('teacher_id', Auth::id())
                    ->with(['academicYear', 'unit']);

        if ($activeYear) {
            $query->where('academic_year_id', $activeYear->id);
        } else {
            $query->orderByDesc('id');
        }

        return $query->first();
    }

    public function index()
    {
        $myClass = $this->getMyClass();

        if (!$myClass) {
            return view('wali_kelas.announcements.empty', ['message' => 'Anda tidak memiliki kelas aktif saat ini.']);
        }

        $announcements = ClassAnnouncement::where('class_id', $myClass->id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('wali_kelas.announcements.index', compact('myClass', 'announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|max:5120', // Max 5MB
        ]);

        $myClass = $this->getMyClass();
        if (!$myClass) abort(403);

        $data = [
            'class_id' => $myClass->id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => true,
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('announcements', $filename, 'public');
            $data['attachment'] = $path;
            $data['original_filename'] = $file->getClientOriginalName();
        }

        ClassAnnouncement::create($data);

        return back()->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function destroy($id)
    {
        $announcement = ClassAnnouncement::findOrFail($id);
        
        // Authorization check
        if ($announcement->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete file if exists
        if ($announcement->attachment) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($announcement->attachment);
        }

        $announcement->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}
