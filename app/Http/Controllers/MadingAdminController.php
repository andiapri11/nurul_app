<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MadingAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with('unit')->latest();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('unit_id') && $request->unit_id != '') {
            $query->where('unit_id', $request->unit_id);
        }

        $announcements = $query->paginate(10);
        $units = Unit::all();
        
        return view('mading_admin.index', compact('announcements', 'units'));
    }

    public function create()
    {
        $units = Unit::all();
        return view('mading_admin.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:news,poster,running_text',
            'unit_id' => 'nullable|exists:units,id',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->route('mading-admin.index')->with('success', 'Konten Mading berhasil ditambahkan');
    }

    public function edit(Announcement $mading_admin)
    {
        $units = Unit::all();
        return view('mading_admin.edit', compact('mading_admin', 'units'));
    }

    public function update(Request $request, Announcement $mading_admin)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:news,poster,running_text',
            'unit_id' => 'nullable|exists:units,id',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($mading_admin->image) {
                Storage::disk('public')->delete($mading_admin->image);
            }
            $data['image'] = $request->file('image')->store('announcements', 'public');
        }

        $mading_admin->update($data);

        return redirect()->route('mading-admin.index')->with('success', 'Konten Mading berhasil diperbarui');
    }

    public function destroy(Announcement $mading_admin)
    {
        if ($mading_admin->image) {
            Storage::disk('public')->delete($mading_admin->image);
        }
        $mading_admin->delete();
        return redirect()->route('mading-admin.index')->with('success', 'Konten Mading berhasil dihapus');
    }
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'Pilih konten yang ingin dihapus');
        }

        $announcements = Announcement::whereIn('id', $ids)->get();
        foreach ($announcements as $announcement) {
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            $announcement->delete();
        }

        return redirect()->route('mading-admin.index')->with('success', count($ids) . ' Konten Mading berhasil dihapus');
    }

    public function bulkUpdate(Request $request)
    {
        $ids = $request->input('ids');
        $status = $request->input('status');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'Pilih konten yang ingin diubah');
        }

        Announcement::whereIn('id', $ids)->update(['is_active' => $status == 'active']);

        return redirect()->route('mading-admin.index')->with('success', count($ids) . ' Konten Mading berhasil diperbarui');
    }
}
