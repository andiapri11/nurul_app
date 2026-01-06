<?php

namespace App\Http\Controllers;

use App\Models\UserSiswa;
use App\Models\Student;
use App\Models\Unit;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    private function getAllowedUnits()
    {
        $user = auth()->user();
        if (in_array($user->role, ['administrator', 'admin', 'direktur'])) {
            return Unit::all();
        }
        // Use getLearningManagementUnits() to allow both Kurikulum and Kepala Sekolah
        return $user->getLearningManagementUnits(); 
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->user()->role === 'guru' && !auth()->user()->isManajemenSekolah()) {
            abort(403, 'Guru tidak diizinkan mengakses daftar siswa global.');
        }
        return $this->getStudentList($request, 'aktif', 'Data Siswa Aktif');
    }

    public function alumni(Request $request)
    {
        if (auth()->user()->role === 'guru' && !auth()->user()->isManajemenSekolah()) {
            abort(403, 'Guru tidak diizinkan mengakses daftar alumni global.');
        }
        return $this->getStudentList($request, 'lulus', 'Data Siswa Alumni');
    }

    public function withdrawn(Request $request)
    {
        if (auth()->user()->role === 'guru' && !auth()->user()->isManajemenSekolah()) {
            abort(403, 'Guru tidak diizinkan mengakses daftar siswa keluar.');
        }
        return $this->getStudentList($request, 'keluar', 'Data Siswa Keluar');
    }

    private function getStudentList(Request $request, $status, $title)
    {
        $allowedUnits = $this->getAllowedUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $query = Student::with(['user', 'unit'])
                        ->whereIn('unit_id', $allowedUnitIds)
                        ->where('status', $status);
        
        // Filter by Unit via Request
        $selectedUnitId = $request->get('unit_id');
        if ($selectedUnitId) {
             if (!in_array($selectedUnitId, $allowedUnitIds)) {
                 abort(403, 'Akses ditolak ke Unit ini.');
             }
             $query->where('unit_id', $selectedUnitId);
        }

        // Identification of Active Year
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        $activeYear = $academicYears->where('status', 'active')->first();
        
        // Default year logic: Only default to active year if in 'aktif' view.
        // For alumni/withdrawn, default to null so we see all records initially.
        if ($status === 'aktif') {
            $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        } else {
            $selectedYearId = $request->get('academic_year_id'); // Optional for historical views
        }
        
        $isActiveYear = ($activeYear && ($selectedYearId == $activeYear->id || !$selectedYearId));

        // Filter by Year
        if ($selectedYearId) {
            if ($status !== 'aktif') {
                // Alumni/Withdrawn/Pindah: Filter by their FINAL class year (their 'graduating' year)
                // Alumni/Withdrawn/Pindah: Filter by their class history in the selected year
                $query->whereHas('classes', function($q) use ($selectedYearId) {
                    $q->where('classes.academic_year_id', $selectedYearId);
                });
            } else {
                // For Active students:
                // If selecting a NON-ACTIVE year, filter by attendance history in that year
                if ($activeYear && $selectedYearId != $activeYear->id) {
                    $query->whereHas('classes', function($q) use ($selectedYearId) {
                        $q->where('classes.academic_year_id', $selectedYearId);
                    });
                }
                // If selecting the active year (or global view), don't filter history 
                // so newly registered/promoted students stay visible.
            }
        }

        // Filter by Class
        $selectedClassId = $request->get('class_id');
        if ($selectedClassId) {
            $query->whereHas('classes', function($sq) use ($selectedClassId) {
                $sq->where('classes.id', $selectedClassId);
            });
        }

        // Eager load classes correctly based on status
        if ($status === 'aktif') {
            // For active students, we mostly care about their class in the selected year for the display column
            $query->with(['classes' => function($q) use ($selectedYearId) {
                if ($selectedYearId) {
                    $q->where('classes.academic_year_id', $selectedYearId);
                }
            }, 'classes.academicYear']);
        } else {
            // For Alumni/Withdrawn, we want ALL history to show the timeline
            $query->with(['classes.academicYear']);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Fetch Classes list for the filter (based on Unit and Year if possible)
        $classes = collect();
        if ($selectedUnitId) {
            $classesQ = SchoolClass::where('unit_id', $selectedUnitId);
            if ($selectedYearId) {
                $classesQ->where('academic_year_id', $selectedYearId);
            }
            $classes = $classesQ->orderBy('name')->get();
        }

        $perPage = $request->get('per_page', 10);
        $students = $query->latest()->paginate($perPage)->appends($request->all());
        $units = $allowedUnits;
        
        return view('students.index', compact('students', 'academicYears', 'selectedYearId', 'selectedUnitId', 'selectedClassId', 'units', 'classes', 'title', 'isActiveYear'));
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,lulus,keluar,pindah,non-aktif',
            'withdrawal_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' // Max 5MB
        ]);

        $student = Student::findOrFail($id);
        
        $allowedUnits = $this->getAllowedUnits();
        if (!in_array($student->unit_id, $allowedUnits->pluck('id')->toArray())) {
            abort(403, 'Akses Ditolak.');
        }

        $student->status = $request->status;
        
        // Handle withdrawal proof upload
        if ($request->status === 'keluar' && $request->hasFile('withdrawal_proof')) {
            $fileName = 'withdrawal_' . $student->nis . '_' . time() . '.' . $request->withdrawal_proof->extension();
            $request->withdrawal_proof->move(public_path('withdrawal_documents'), $fileName);
            $student->withdrawal_proof = $fileName;
        }
        
        // Sync class history if moving to inactive statuses
        if (in_array($request->status, ['lulus', 'pindah', 'keluar']) && $student->class_id) {
            if (!$student->classes()->where('classes.id', $student->class_id)->exists()) {
                $student->classes()->attach($student->class_id);
            }
            
            // Automatically deactivate user login for withdrawn or transferred students ONLY
            if (in_array($request->status, ['pindah', 'keluar']) && $student->user) {
                $student->user->update(['status' => 'non-aktif']);
            }
        }
        
        // Handle reactivation: delete withdrawal proof if exists and reactivate user login
        if ($request->status === 'aktif') {
            if ($student->withdrawal_proof) {
                $oldFilePath = public_path('withdrawal_documents/' . $student->withdrawal_proof);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
                $student->withdrawal_proof = null;
            }
            
            // Automatically reactivate user login
            if ($student->user) {
                $student->user->update([
                    'status' => 'aktif',
                    'locked_at' => null,
                    'login_attempts' => 0
                ]);
            }
        }

        $student->save();

        return redirect()->back()->with('success', 'Status siswa berhasil diperbarui ke ' . ucfirst($request->status));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isDirektur()) {
             abort(403, 'Akses ditolak: Hanya Administrator yang dapat menambah siswa.');
        }

        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        if (!$activeYear) {
            return redirect()->route('students.index')->with('error', 'Tidak ada Tahun Pelajaran yang Aktif. Aktifkan tahun pelajaran terlebih dahulu untuk menambah siswa.');
        }

        $units = $this->getAllowedUnits();
        if ($units->isEmpty()) {
             return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke unit manapun.');
        }

        // Only show classes for active year
        $classes = SchoolClass::where('academic_year_id', $activeYear->id)->get(); 
        return view('students.create', compact('units', 'classes', 'activeYear'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isDirektur()) {
             abort(403, 'Akses ditolak: Hanya Administrator yang dapat menambah siswa.');
        }

        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        if (!$activeYear) {
            return redirect()->route('students.index')->with('error', 'Penambahan siswa gagal: Tidak ada Tahun Pelajaran yang Aktif.');
        }

        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();

        $request->validate([
            // User Validation
            'name' => 'nullable|string|max:255|unique:user_siswa,username', // 'name' input is actually username
            'email' => 'required|string|email|max:255|unique:user_siswa',
            'password' => 'required|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Student Validation
            'nis' => 'required|string|unique:students',
            'nisn' => 'required|string|unique:students',
            'unit_id' => 'required|exists:units,id',
            // 'class_id' => 'nullable|exists:classes,id',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required', // Custom format handling
            'alamat' => 'required|string',
            'nama_wali' => 'required|string|max:255',
            'no_hp_wali' => 'required|string|max:20',
            'status' => 'required|in:aktif,lulus,keluar,pindah,non-aktif',
        ]);
        
        if (!in_array($request->unit_id, $allowedIds)) {
             abort(403, 'Akses ditolak. Anda tidak berhak menambahkan siswa ke Unit ini.');
        }

        DB::beginTransaction();

        try {
            // Handle Username Generation
            $username = $request->name;
            if (!$username) {
                // Generate: firstname + nis or random
                $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode(' ', $request->nama_lengkap)[0]));
                $username = $cleanName . ($request->nis ?? rand(100,999));
            }

            // Handle Date Format
            try {
                $birthDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d');
            } catch (\Exception $e) {
                // Fallback or assume Y-m-d
                $birthDate = $request->tanggal_lahir;
            }

            // Create UserSiswa
            $userData = [
                'name' => $request->nama_lengkap, // Use full name for the User record name
                'username' => $username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'plain_password' => $request->password,
            ];

            if ($request->hasFile('photo')) {
                $imageName = time().'.'.$request->photo->extension();
                $request->photo->move(public_path('photos'), $imageName);
                $userData['photo'] = $imageName;
            }

            $user = UserSiswa::create($userData);

            // Create Student linked to UserSiswa
            $student = Student::create([
                'user_siswa_id' => $user->id,
                'unit_id' => $request->unit_id,
                'class_id' => $request->class_id ?? null,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'is_boarding' => $request->is_boarding ?? 0,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $birthDate, // Use parsed date
                'alamat' => $request->alamat,
                'nama_wali' => $request->nama_wali,
                'no_hp_wali' => $request->no_hp_wali,
                'status' => $request->status,
            ]);
            
            // Sync Class History
            if ($request->class_id) {
                $student->classes()->syncWithoutDetaching([$request->class_id]);
            }

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Student created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            if (isset($imageName) && file_exists(public_path('photos/' . $imageName))) {
                unlink(public_path('photos/' . $imageName));
            }
            return redirect()->back()->with('error', 'Failed to create student: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $student = Student::with('user')->findOrFail($id);
        
        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();
        if (!in_array($student->unit_id, $allowedIds)) {
             abort(403, 'Akses Ditolak: Anda tidak dapat mengedit siswa dari unit ini.');
        }

        $units = $allowedUnits;
        $classes = SchoolClass::with('academicYear')->get();
        return view('students.edit', compact('student', 'units', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user; // This is now UserSiswa
        
        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();
        if (!in_array($student->unit_id, $allowedIds)) {
             abort(403, 'Akses Ditolak: Anda tidak dapat mengedit siswa dari unit ini.');
        }

        // Remove credentials from request if not admin
        if (!auth()->user()->isDirektur()) {
             $request->request->remove('username');
             $request->request->remove('password');
             // Typically we keep email readonly in view but submitted? 
             // If readonly input is submitted in HTML, it is sent.
             // If disabled, it is not.
             // Our view uses 'readonly' attribute, so it IS sent.
             // But we want to prevent changing it validly.
             // So we should validate it matches existing? Or just ignore it?
             // Let's just ignore it (remove from request) so user properties aren't updated.
             $request->request->remove('email');
        }

        $rules = [
            // Student Validation
            'nis' => ['required', 'string', Rule::unique('students')->ignore($student->id)],
            'nisn' => ['required', 'string', Rule::unique('students')->ignore($student->id)],
            'unit_id' => 'required|exists:units,id',
            // 'class_id' => 'nullable|exists:classes,id',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required', // Custom format handling
            'alamat' => 'required|string',
            'alamat_rt' => 'nullable|string|max:3',
            'alamat_rw' => 'nullable|string|max:3',
            'desa' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'nama_wali' => 'required|string|max:255',
            'no_hp_wali' => 'required|string|max:20',
            'status' => 'required|in:aktif,lulus,keluar,pindah,non-aktif',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Add Credential Rules Only for Admin
        if (auth()->user()->isDirektur()) {
            $rules['username'] = ['nullable', 'string', 'max:255', Rule::unique('user_siswa')->ignore($user->id)];
            $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique('user_siswa')->ignore($user->id)];
            $rules['password'] = 'nullable|string|min:8';
        }

        $request->validate($rules);
        
        if (!in_array($request->unit_id, $allowedIds)) {
             abort(403, 'Akses Ditolak: Anda tidak dapat memindahkan siswa ke unit yang tidak Anda kelola.');
        }

        DB::beginTransaction();

        try {
            // Update UserSiswa
            $user->name = $request->nama_lengkap; // Sync name with full name
            
            // Only update credentials if admin (and request has them, which it should if admin)
            if (auth()->user()->isDirektur()) {
                if ($request->filled('username')) {
                     $user->username = $request->username;
                }
                if ($request->filled('email')) {
                    $user->email = $request->email;
                }
                
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                    $user->plain_password = $request->password;
                }
            }

            if ($request->hasFile('photo')) {
                if ($user->photo && file_exists(public_path('photos/' . $user->photo))) {
                    unlink(public_path('photos/' . $user->photo));
                }

                $imageName = time().'.'.$request->photo->extension();
                $request->photo->move(public_path('photos'), $imageName);
                $user->photo = $imageName;
            }

            $user->save();

            // Handle Date Format for Update
            try {
                $birthDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d');
            } catch (\Exception $e) {
                // Fallback or assume Y-m-d
                $birthDate = $request->tanggal_lahir;
            }

            // Update Student
            $student->update([
                'unit_id' => $request->unit_id,
                'class_id' => $request->class_id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'is_boarding' => $request->is_boarding ?? 0,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $birthDate,
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
                'status' => $request->status,
            ]);

            // Automatically reactivate or deactivate user login based on status
            if ($request->status === 'aktif') {
                if ($student->user) {
                    $student->user->update([
                        'status' => 'aktif',
                        'locked_at' => null,
                        'login_attempts' => 0
                    ]);
                }
            } elseif (in_array($request->status, ['pindah', 'keluar'])) {
                if ($student->user) {
                    $student->user->update(['status' => 'non-aktif']);
                }
            }
            
            $student->save();
            
            // Sync Class History
            if ($request->class_id) {
                $student->classes()->syncWithoutDetaching([$request->class_id]);
            }

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Student updated successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update student: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        
        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();
        if (!in_array($student->unit_id, $allowedIds)) {
             abort(403, 'Akses Ditolak: Anda tidak dapat menghapus siswa dari unit ini.');
        }

        $user = $student->user; // UserSiswa

        if ($user && $user->photo && file_exists(public_path('photos/' . $user->photo))) {
            unlink(public_path('photos/' . $user->photo));
        }

        // Deleting user_siswa will cascade delete student due to db constraints
        if ($user) {
            $user->delete();
        } else {
            // Fallback if user missing
            $student->delete();
        }

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
            'action' => 'required|in:pindah,keluar,hapus,lulus,aktif',
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $count = count($ids);
        
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();

        switch ($action) {
            case 'aktif':
                $studentsToActivate = Student::whereIn('id', $ids)->get();
                foreach ($studentsToActivate as $student) {
                    if ($student->withdrawal_proof) {
                        $oldFilePath = public_path('withdrawal_documents/' . $student->withdrawal_proof);
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                    $student->update(['status' => 'aktif', 'withdrawal_proof' => null]);
                }
                $message = "$count siswa berhasil diaktifkan kembali.";
                break;
            case 'lulus':
                $this->updateStatusAndSyncClass($ids, 'lulus', $activeYear);
                $message = "$count siswa berhasil di-set sebagai Alumni.";
                break;
            case 'pindah':
                $this->updateStatusAndSyncClass($ids, 'pindah', $activeYear);
                $message = "$count siswa berhasil di-set sebagai Pindah.";
                break;
            case 'keluar':
                $this->updateStatusAndSyncClass($ids, 'keluar', $activeYear);
                $message = "$count siswa berhasil di-set sebagai Keluar.";
                break;
            case 'hapus':
                // For delete, we need to handle user deletion and photo cleanup
                $students = Student::with('user')->whereIn('id', $ids)->get();
                foreach ($students as $student) {
                    $user = $student->user;
                    if ($user && $user->photo && file_exists(public_path('photos/' . $user->photo))) {
                        unlink(public_path('photos/' . $user->photo));
                    }
                    if ($user) {
                        $user->delete(); // Cascades to student
                    } else {
                        $student->delete();
                    }
                }
                $message = "$count siswa berhasil dihapus.";
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Helper to update status and ensure student has class record for active year if they have a current class_id
     */
    private function updateStatusAndSyncClass($ids, $status, $activeYear)
    {
        $students = Student::whereIn('id', $ids)->get();
        foreach ($students as $student) {
            $student->status = $status;
            
            // If student has a current class_id and we have an active year, 
            // ensure it's recorded in history (class_student)
            if ($student->class_id) {
                // Attach to pivot if not already exists for this specific class
                if (!$student->classes()->where('classes.id', $student->class_id)->exists()) {
                    $student->classes()->attach($student->class_id);
                }
            }

            // Automatically deactivate user login for withdrawn or transferred students ONLY
            if (in_array($status, ['pindah', 'keluar'])) {
                if ($student->user) {
                    $student->user->update(['status' => 'non-aktif']);
                }
            }
            
            $student->save();
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_siswa.csv"',
        ];

        // Changed unit to unit_id
        $columns = ['NIS', 'NISN', 'Nama Lengkap', 'Unit ID', 'Username', 'Password', 'Jenis Kelamin (L/P)' ,'Tempat Lahir', 'Tanggal Lahir (YYYY-MM-DD)', 'Nama Wali', 'No HP Wali'];
        
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Example Row
            fputcsv($file, ['12345', '0012345678', 'Contoh Siswa', '1', 'siswa123', 'password123', 'L', 'Jakarta', '2005-01-01', 'Budi Santoso', '08123456789']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Strictly reject binary Excel files for now
        if (in_array($extension, ['xlsx', 'xls'])) {
             return back()->with('error', '<strong>Gagal!</strong> Format Excel (.xlsx/.xls) tidak didukung. Silakan buka file tersebut di Excel, lalu pilih "Save As" (Simpan Sebagai) dan pilih format <strong>CSV (Comma delimited) (*.csv)</strong> sebelum diupload.');
        }
        
        if ($extension !== 'csv' && $extension !== 'txt') {
             return back()->with('error', 'Format file tidak dikenali. Pastikan file berakhiran <strong>.csv</strong>');
        }

        $path = $file->getRealPath();
        $content = file_get_contents($path);
        
        // Remove UTF-8 BOM if present
        $bom = pack('H*','EFBBBF');
        $content = preg_replace("/^$bom/", '', $content);
        
        $lines = preg_split('/\r\n|\r|\n/', $content); 
        $lines = array_filter($lines, 'trim'); // Remove empty lines
        
        if (empty($lines)) {
             return redirect()->back()->with('error', 'File kosong atau format tidak terbaca.');
        }

        // Detect Delimiter (Comma or Semicolon)
        $firstLine = reset($lines);
        $commaCount = substr_count($firstLine, ',');
        $semicolonCount = substr_count($firstLine, ';');
        $delimiter = ($semicolonCount > $commaCount) ? ';' : ',';

        $data = [];
        foreach($lines as $line) {
            $data[] = str_getcsv($line, $delimiter); 
        }

        $header = array_shift($data); // Remove header row

        // Normalize headers
        $header = array_map(function($h) {
            // Remove non-printable characters and BOM
            $h = preg_replace('/[\x00-\x1F\x7F]/', '', $h);
            return strtolower(trim($h));
        }, $header);

        // Helper to clean special characters from input data (fixes SQLSTATE[HY000]: General error: 1366)
        $cleanStr = function($str) {
            if (!$str) return $str;
            // Convert common "smart" quotes/apostrophes to standard ones
            $search = [chr(145), chr(146), chr(147), chr(148), chr(151), '‘', '’', '“', '”', '–'];
            $replace = ["'", "'", '"', '"', '-', "'", "'", '"', '"', '-'];
            $str = str_replace($search, $replace, $str);
            // Remove any other non-UTF8 characters that might break MySQL
            return trim(preg_replace('/[^\x20-\x7E\xA0-\xFF]/', '', $str));
        };
        
        $getIndex = function($keywords) use ($header) {
            if (!is_array($keywords)) $keywords = [$keywords];
            foreach ($header as $key => $val) {
                foreach($keywords as $keyword) {
                     if (str_contains($val, $keyword)) return $key;
                }
            }
            return false;
        };

        $idx_nis = $getIndex(['nis', 'induk']);
        $idx_nisn = $getIndex('nisn');
        $idx_nama = $getIndex(['nama', 'name', 'lengkap']);
        $idx_unit = $getIndex(['unit_id', 'unit']);
        $idx_user = $getIndex(['username', 'user']);
        $idx_pass = $getIndex(['password', 'sandi', 'pass']);
        $idx_jk  = $getIndex(['jenis', 'sex', 'gender', 'jk']);
        $idx_tgl = $getIndex(['tanggal', 'date', 'lahir']);
        $idx_tmpt = $getIndex(['tempat', 'place']);
        $idx_wali = $getIndex(['wali', 'parent', 'ayah', 'ibu']);
        $idx_hp_wali = $getIndex(['hp', 'telp', 'phone', 'wa']);
        $idx_alamat = $getIndex(['alamat', 'address']);

        if ($idx_nis === false || $idx_nama === false || $idx_unit === false || $idx_user === false || $idx_pass === false) {
             return redirect()->back()->with('error', "Format header salah atau Delimiter tidak dikenali. Wajib ada kolom: NIS, Nama, Unit ID, Username, Password.");
        }

        // PRE-VALIDATION PHASE
        $errors = [];
        $validRows = [];
        $existingNIS = Student::pluck('nis')->toArray();
        $existingUsernames = UserSiswa::pluck('username')->toArray();
        $fileNIS = [];
        $fileUsernames = [];

        foreach ($data as $rowIndex => $row) {
            $rowNum = $rowIndex + 2; 
            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            }
            
            $nis      = $cleanStr($row[$idx_nis] ?? '');
            $nisn     = $cleanStr($row[$idx_nisn] ?? '');
            $nama     = $cleanStr($row[$idx_nama] ?? '');
            $unitInput= $cleanStr($row[$idx_unit] ?? '');
            $username = $cleanStr($row[$idx_user] ?? '');
            $password = $cleanStr($row[$idx_pass] ?? '');
            
            if ($nis == '' && $nama == '' && $username == '') continue;

            // 1. Required Fields
            if ($nis == '' || $nama == '' || $unitInput == '' || $username == '' || $password == '') {
                $errors[] = "Baris $rowNum: Semua kolom wajib harus diisi (NIS, Nama, Unit, Username, Password).";
                continue;
            }

            // 2. Unit ID Validation
            $finalUnitId = null;
            if (is_numeric($unitInput)) {
                $finalUnitId = $unitInput;
            } else {
                $unit = \App\Models\Unit::where('name', 'LIKE', '%' . $unitInput . '%')
                    ->orWhere('code', 'LIKE', '%' . $unitInput . '%')
                    ->first();
                if ($unit) $finalUnitId = $unit->id;
            }

            if (!$finalUnitId || !\App\Models\Unit::find($finalUnitId)) {
                 $errors[] = "Baris $rowNum: Unit '$unitInput' tidak valid atau tidak ditemukan.";
                 continue;
            }

            // 3. NISN Check (optional but recommended)
            if ($nisn == '') {
                $errors[] = "Baris $rowNum: NISN wajib diisi sesuai ketentuan sekolah.";
                continue;
            }

            // 4. Duplicate Check (In DB)
            if (in_array($nis, $existingNIS)) {
                $errors[] = "Baris $rowNum: NIS '$nis' sudah terdaftar.";
                continue;
            }
            if (in_array($username, $existingUsernames)) {
                 $errors[] = "Baris $rowNum: Username '$username' sudah terdaftar.";
                 continue;
            }

            // 5. Duplicate Check (In File)
            if (in_array($nis, $fileNIS)) $errors[] = "Baris $rowNum: NIS '$nis' duplikat di file.";
            if (in_array($username, $fileUsernames)) $errors[] = "Baris $rowNum: Username '$username' duplikat di file.";

            $fileNIS[] = $nis;
            $fileUsernames[] = $username;

            // Optional: Birth date
            $birthDateVal = ($idx_tgl !== false) ? trim($row[$idx_tgl] ?? '') : '';

            $validRows[] = [
                'nis' => $nis,
                'nisn' => $nisn,
                'nama' => $nama,
                'unit_id' => $finalUnitId,
                'username' => $username,
                'password' => $password,
                'gender' => ($idx_jk !== false) ? $cleanStr($row[$idx_jk] ?? 'L') : 'L',
                'birth_date' => $birthDateVal,
                'birth_place' => ($idx_tmpt !== false) ? $cleanStr($row[$idx_tmpt] ?? 'Unknown') : 'Unknown',
                'guardian_name' => ($idx_wali !== false) ? $cleanStr($row[$idx_wali] ?? 'Unknown') : 'Unknown',
                'guardian_phone' => ($idx_hp_wali !== false) ? $cleanStr($row[$idx_hp_wali] ?? '-') : '-',
                'address' => ($idx_alamat !== false) ? $cleanStr($row[$idx_alamat] ?? '-') : '-',
            ];
        }

        if (count($errors) > 0) {
            $showErrors = array_slice($errors, 0, 10);
            $msg = "Gagal Upload! Ditemukan " . count($errors) . " kesalahan:<br><ul>";
            foreach($showErrors as $err) $msg .= "<li>$err</li>";
            if(count($errors) > 10) $msg .= "<li>...dan " . (count($errors) - 10) . " kesalahan lainnya.</li>";
            $msg .= "</ul>Data tidak disimpan.";
            return redirect()->back()->with('error', $msg);
        }

        if (empty($validRows)) {
            return back()->with('error', 'Tidak ada data valid yang ditemukan untuk diimport.');
        }

        // Insert Phase
        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($validRows as $d) {
                // Create User
                $user = UserSiswa::create([
                    'name' => $d['nama'],
                    'username' => $d['username'],
                    'email' => $d['username'] . '@student.nurulilmi.id',
                    'password' => Hash::make($d['password']),
                    'plain_password' => $d['password'],
                    'role' => 'siswa',
                    'status' => 'aktif'
                ]);

                // Parse Gender
                $jk = 'L';
                if (!empty($d['gender'])) {
                     $val = strtoupper($d['gender']);
                     if (str_contains($val, 'P') || str_contains($val, 'W') || str_contains($val, 'PEREMPUAN') || str_contains($val, 'FEMALE')) {
                         $jk = 'P';
                     }
                }

                // Parse Date
                $born = null;
                if (!empty($d['birth_date'])) {
                    try {
                        // Support multiple formats d/m/Y or Y-m-d
                        if (str_contains($d['birth_date'], '/')) {
                            $born = \Carbon\Carbon::createFromFormat('d/m/Y', $d['birth_date'])->format('Y-m-d');
                        } else {
                            $born = \Carbon\Carbon::parse($d['birth_date'])->format('Y-m-d');
                        }
                    } catch (\Exception $e) {
                         // null is fine
                    }
                }

                // Create Student
                Student::create([
                    'user_siswa_id' => $user->id,
                    'unit_id' => $d['unit_id'],
                    'class_id' => null,
                    'nis' => $d['nis'],
                    'nisn' => $d['nisn'],
                    'nama_lengkap' => $d['nama'],
                    'jenis_kelamin' => $jk,
                    'tempat_lahir' => $d['birth_place'],
                    'tanggal_lahir' => $born,
                    'nama_wali' => $d['guardian_name'],
                    'no_hp_wali' => $d['guardian_phone'],
                    'alamat' => $d['address'],
                    'status' => 'aktif'
                ]);
                $count++;
            }
            DB::commit();
            return redirect()->back()->with('success', "Import Berhasil! $count siswa telah ditambahkan.");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menyimpan: ' . $e->getMessage());
        }
    }
    public function export(Request $request)
    {
        $allowedUnits = $this->getAllowedUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $query = Student::with(['user', 'unit', 'schoolClass'])
                        ->whereIn('unit_id', $allowedUnitIds); 
        
        // Filter by Unit via Request
        if ($request->has('unit_id') && $request->unit_id != '') {
             if (!in_array($request->unit_id, $allowedUnitIds)) {
                 abort(403, 'Akses ditolak ke Unit ini.');
             }
             $query->where('unit_id', $request->unit_id);
        }

        // Filter by Year / Class
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        $activeYear = $academicYears->where('status', 'active')->first();
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        
        if ($selectedYearId) {
             $query->where(function($q) use ($selectedYearId) {
                 $q->whereHas('classes', function($sq) use ($selectedYearId) {
                     $sq->where('classes.academic_year_id', $selectedYearId);
                 })
                 ->orWhere('status', 'aktif');
             });
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }
        
        $students = $query->latest()->get();

        // Check if Maatwebsite Excel exists
        if (class_exists('Maatwebsite\Excel\Facades\Excel')) {
             // Implementation for real Excel if library exists (not implemented here to keep it simple/dependency-free if not installed)
        }

        // Generate HTML Table for XLS (Pseudo-Excel) or CSV
        // User requested .xls format explicitly. HTML Table with header usually opens correctly in Excel with a warning.
        // OR CSV. "Format xls" often implies they want to open it in Excel.
        // Let's stick to CSV but name it .csv to avoid warnings, OR .xls if they really want.
        // For reliability, I will give them a CSV.
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="Data_Siswa_Nurul_Ilmi.xls"', // Giving .xls as requested
             'Pragma' => 'no-cache', 
             'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', 
             'Expires' => '0'
        ];

        // We use HTML Table method for true "xls" experience without library
        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 in case I switched to CSV
            // fwrite($file, "\xEF\xBB\xBF");
            
            // HTML Table Start
            echo '<html><head><meta charset="utf-8"></head><body>';
            echo '<table border="1">';
            echo '<thead><tr>';
            echo '<th style="background-color: #f2f2f2;">No</th>';
            echo '<th style="background-color: #f2f2f2;">Nama Lengkap</th>';
            echo '<th style="background-color: #f2f2f2;">NISN</th>';
            echo '<th style="background-color: #f2f2f2;">NIS</th>';
            echo '<th style="background-color: #f2f2f2;">Username</th>';
            echo '<th style="background-color: #f2f2f2;">Password</th>';
            echo '<th style="background-color: #f2f2f2;">Unit</th>';
            echo '<th style="background-color: #f2f2f2;">Kelas Saat Ini</th>';
            echo '</tr></thead><tbody>';
            
            foreach ($students as $index => $student) {
                $user = $student->user;
                $password = $user ? $user->plain_password : '-'; // Assuming plain_password exists
                $className = '-';
                $currentClass = $student->classes->where('academic_year_id', \App\Models\AcademicYear::where('status', 'active')->first()->id ?? 0)->first();
                if ($currentClass) {
                    $className = $currentClass->name;
                } elseif ($student->schoolClass->isNotEmpty()) {
                    $className = $student->schoolClass->first()->name; // Fallback
                }
                
                echo '<tr>';
                echo '<td>' . ($index + 1) . '</td>';
                echo '<td>' . htmlspecialchars($student->nama_lengkap) . '</td>';
                echo '<td style="mso-number-format:\'\@\'">\'' . $student->nisn . '</td>'; // Force text for NISN with '
                echo '<td style="mso-number-format:\'\@\'">\'' . $student->nis . '</td>';   // Force text for NIS with '
                echo '<td>' . ($user ? htmlspecialchars($user->username) : '-') . '</td>';
                echo '<td>' . htmlspecialchars($password) . '</td>';
                echo '<td>' . htmlspecialchars($student->unit->name ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($className) . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody></table></body></html>';
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
