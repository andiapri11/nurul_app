<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\UserSiswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class WaliKelasStudentController extends Controller
{
    /**
     * Get the Wali Kelas's authorized class and unit.
     */
    private function getAuthorizedClass()
    {
        $user = auth()->user();
        $academicYear = \App\Models\AcademicYear::where('status', 'active')->first();
        
        if (!$academicYear) {
            return null;
        }

        // Find the class assigned to this Wali Kelas for the active academic year
        // Based on User model, the relationship uses 'teacher_id'
        
        $waliKelasClass = SchoolClass::where('teacher_id', $user->id)
                            ->where('academic_year_id', $academicYear->id)
                            ->first();

        return $waliKelasClass;
    }

    public function index(Request $request) // Added Request injection
    {
        $user = auth()->user();
        
        // --- Logic for Administrator ---
        if ($user->isDirektur()) {
            $units = \App\Models\Unit::all();
            
            // Default to first unit if none selected
            $selectedUnitId = $request->get('unit_id', $units->first()->id ?? null);
            
            // Fetch classes based on selected unit
            $classes = SchoolClass::when($selectedUnitId, function($q) use ($selectedUnitId) {
                            $q->where('unit_id', $selectedUnitId);
                        })->orderBy('name')->get();
            
            // Default to first class if none selected
            $selectedClassId = $request->get('class_id', $classes->first()->id ?? null);
            
            if ($selectedClassId) {
                $schoolClass = SchoolClass::with(['academicYear', 'unit'])->find($selectedClassId);
                $students = Student::whereHas('classes', function($q) use ($selectedClassId) {
                                    $q->where('classes.id', $selectedClassId);
                                })
                                ->orderBy('nama_lengkap')
                                ->get();
            } else {
                $schoolClass = null; // No class selected/available
                $students = collect();
            }

            return view('wali_kelas.students.index', compact('students', 'schoolClass', 'units', 'classes', 'selectedUnitId', 'selectedClassId'));
        }

        // --- Logic for Wali Kelas (Standard) ---
        $schoolClass = $this->getAuthorizedClass();

        if (!$schoolClass) {
             return view('wali_kelas.students.index', ['students' => collect(), 'error' => 'Anda belum ditugaskan sebagai Wali Kelas untuk Tahun Ajaran aktif.']);
        }

        $students = Student::whereHas('classes', function($q) use ($schoolClass) {
                        $q->where('classes.id', $schoolClass->id);
                    })
                        ->orderBy('nama_lengkap')
                        ->get();

        return view('wali_kelas.students.index', compact('schoolClass', 'students'));
    }

    public function edit(Request $request, $id) // Added Request just in case, technically not checking filter here but authorization
    {
        $user = auth()->user();
        
        // Authorization Check
        if ($user->isDirektur()) {
             // Admin always authorized, fetch student directly
             $student = Student::where('id', $id)->firstOrFail();
             // We don't enforce class scope for edit if admin, 
             // BUT user asked for "view and edit" in context of this module.
             // Admin uses specific controller usually, but if reusing this view/path:
             return view('wali_kelas.students.edit', compact('student'));   
        }

        // Normal Wali Kelas Check
        $schoolClass = $this->getAuthorizedClass();
        if (!$schoolClass) abort(403, 'Unauthorized');

        $student = Student::where('id', $id)
                    ->whereHas('classes', function($q) use ($schoolClass) {
                        $q->where('classes.id', $schoolClass->id);
                    })->firstOrFail();

        return view('wali_kelas.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        // Authorization Scope
        if ($user->role === 'administrator') {
             // Admin scope
             $student = Student::where('id', $id)->firstOrFail();
        } else {
             // Wali Kelas scope
            $schoolClass = $this->getAuthorizedClass();
            if (!$schoolClass) abort(403, 'Unauthorized');
            $student = Student::where('id', $id)
                        ->whereHas('classes', function($q) use ($schoolClass) {
                            $q->where('classes.id', $schoolClass->id);
                        })->firstOrFail();
        }

        // Validation based on requirements:
        // Data Akademik: Nama Lengkap, nis, nisn, jenis kelamin
        // Detail Alamat & Pribadi: semua
        // Wali: semua
        // Unggah photo
        
        $request->validate([
            // Akademik
            'nama_lengkap' => 'required|string|max:255',
            'nis' => 'nullable|string|max:20',
            'nisn' => 'nullable|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            
            // Pribadi & Alamat
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date_format:d/m/Y',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'alamat_rt' => 'nullable|string|max:10',
            'alamat_rw' => 'nullable|string|max:10',
            'desa' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kota' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100', // Optional for student personal email
            
            // Wali
            'nama_wali' => 'nullable|string|max:255',
            'no_hp_wali' => 'nullable|string|max:20',
            // Add other wali fields if exist in DB?
            
            // Photo
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Update Student Data
            $student->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir, // Cast handled in model if formatting needed
                'agama' => $request->agama,
                'alamat' => $request->alamat,
                'alamat_rt' => $request->alamat_rt,
                'alamat_rw' => $request->alamat_rw,
                'desa' => $request->desa,
                'kecamatan' => $request->kecamatan,
                'kota' => $request->kota,
                'kode_pos' => $request->kode_pos,
                'no_hp' => $request->no_hp,
                'nama_wali' => $request->nama_wali,
                'no_hp_wali' => $request->no_hp_wali,
            ]);

            // Handle Photo Upload
            // Photo is usually stored in UserSiswa or Student?
            // UserSiswa has 'photo', Student does not in provided view.
            // Let's check UserSiswa relation.
            if ($student->user_siswa_id) {
                $userSiswa = \App\Models\UserSiswa::find($student->user_siswa_id);
                if ($userSiswa) {
                    $userSiswa->name = $request->nama_lengkap; // Sync name
                    
                    if ($request->hasFile('photo')) {
                        // Delete old photo if it exists
                        if ($userSiswa->photo && file_exists(public_path('photos/' . $userSiswa->photo))) {
                            unlink(public_path('photos/' . $userSiswa->photo));
                        }
                        
                        // Store directly to public/photos to match Admin logic (based on view 'asset(photos/...)')
                        $file = $request->file('photo');
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('photos'), $filename);
                        
                        $userSiswa->photo = $filename;
                    }
                    $userSiswa->save();
                }
            }

            DB::commit();
            return redirect()->route('wali-kelas.students.index')->with('success', 'Data siswa berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}
