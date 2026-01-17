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
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdminStudentController extends Controller
{
    public function index(Request $request)
    {
        $filterData = $this->getFilterData($request);
        $isActiveYear = $filterData['isActiveYear'];
        $query = $this->getBaseQuery($request); // Remove status filter to show all students
        $perPage = $request->get('per_page', 10);
        $students = $query->paginate($perPage)->appends($request->all());
        $title = "Manajemen User Siswa (Semua)";
        return view('admin.students.index', array_merge(compact('students', 'title', 'isActiveYear'), $filterData));
    }

    public function alumni(Request $request)
    {
        $filterData = $this->getFilterData($request);
        $isActiveYear = $filterData['isActiveYear'];
        $query = $this->getBaseQuery($request)->where('status', 'lulus');
        $perPage = $request->get('per_page', 10);
        $students = $query->paginate($perPage)->appends($request->all());
        $title = "Data Siswa Alumni";
        return view('admin.students.index', array_merge(compact('students', 'title', 'isActiveYear'), $filterData));
    }

    public function withdrawn(Request $request)
    {
        $filterData = $this->getFilterData($request);
        $isActiveYear = $filterData['isActiveYear'];
        $query = $this->getBaseQuery($request)->where('status', 'keluar');
        $perPage = $request->get('per_page', 10);
        $students = $query->paginate($perPage)->appends($request->all());
        $title = "Data Siswa Keluar";
        return view('admin.students.index', array_merge(compact('students', 'title', 'isActiveYear'), $filterData));
    }

    private function getFilterData(Request $request)
    {
        $units = \App\Models\Unit::all();
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        
        $selectedUnitId = $request->unit_id;
        $selectedYearId = $request->academic_year_id;
        $selectedClassId = $request->class_id;

        $classes = collect();
        if ($selectedUnitId) {
            $classesQ = \App\Models\SchoolClass::where('unit_id', $selectedUnitId);
            if ($selectedYearId) {
                $classesQ->where('academic_year_id', $selectedYearId);
            }
            $classes = $classesQ->orderBy('name')->get();
        }

        $activeYear = $academicYears->where('status', 'active')->first();
        $isActiveYear = ($activeYear && ($selectedYearId == $activeYear->id || !$selectedYearId));

        return compact('units', 'academicYears', 'classes', 'selectedUnitId', 'selectedYearId', 'selectedClassId', 'isActiveYear');
    }

    private function getBaseQuery(Request $request)
    {
        $query = Student::with(['user', 'unit', 'schoolClass.academicYear', 'classes.academicYear']);

        // Filter Unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Year - ONLY for non-active status by default
        // Get status if possible, but here were in base query.
        // Let's check the current route or request to see if we are in 'aktif' mode
        $isAktifView = str_contains($request->url(), 'alumni') === false && str_contains($request->url(), 'withdrawn') === false;

        if ($request->filled('academic_year_id')) {
            $yearId = $request->academic_year_id;
            $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();

            // If it's NOT the active students view, filter by their FINAL year (Graduation Year)
            if (!$isAktifView) {
                $query->whereHas('schoolClass', function($q) use ($yearId) {
                    $q->where('classes.academic_year_id', $yearId);
                });
            } else {
                // Active Students: If selecting a NON-ACTIVE year, filter by attendance history
                if ($activeYear && $yearId != $activeYear->id) {
                    $query->whereHas('classes', function($q) use ($yearId) {
                        $q->where('classes.academic_year_id', $yearId);
                    });
                }
            }
            
            // For eager loading display, we always filter classes by the selected year
            $query->with(['classes' => function($q) use ($yearId) {
                $q->where('classes.academic_year_id', $yearId);
            }, 'classes.academicYear']);
        } else {
            // No year filter, load all history
            $query->with(['classes.academicYear']);
        }

        // Filter Class
        if ($request->filled('class_id')) {
            $classId = $request->class_id;
            $query->where(function($q) use ($classId) {
                $q->where('class_id', $classId)
                  ->orWhereHas('classes', function($sq) use ($classId) {
                      $sq->where('classes.id', $classId);
                  });
            });
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('username', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('nama_lengkap', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('nama_lengkap', 'desc');
                    break;
                case 'nis_asc':
                    $query->orderBy('nis', 'asc');
                    break;
                case 'nis_desc':
                    $query->orderBy('nis', 'desc');
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        return $query;
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,lulus,keluar,pindah,non-aktif'
        ]);

        $student = Student::findOrFail($id);
        $student->status = $request->status;
        
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

    public function toggleUserStatus($id)
    {
        $student = Student::with('user')->findOrFail($id);
        
        if (!$student->user) {
            return redirect()->back()->with('error', 'Siswa ini belum memiliki akun user.');
        }

        $user = $student->user;
        // Handle null status by defaulting to 'aktif'
        $currentStatus = $user->status ?? 'aktif';
        // Toggle: If currently 'aktif', set to 'non-aktif', otherwise set to 'aktif'
        $user->status = ($currentStatus === 'aktif') ? 'non-aktif' : 'aktif';
        
        // Reset login attempts if activating
        if ($user->status === 'aktif') {
             $user->locked_at = null;
             $user->login_attempts = 0;
        }
        $user->save();

        $statusText = $user->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Akun login siswa berhasil $statusText.");
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
                    
                    // Automatically reactivate user login
                    if ($student->user) {
                        $student->user->update([
                            'status' => 'aktif',
                            'locked_at' => null,
                            'login_attempts' => 0
                        ]);
                    }
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
                $students = Student::with('user')->whereIn('id', $ids)->get();
                foreach ($students as $student) {
                    $user = $student->user;
                    if ($user && $user->photo && file_exists(public_path('photos/' . $user->photo))) {
                        unlink(public_path('photos/' . $user->photo));
                    }
                    if ($user) {
                        $user->delete();
                    } else {
                        $student->delete();
                    }
                }
                $message = "$count siswa berhasil dihapus.";
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    private function updateStatusAndSyncClass($ids, $status, $activeYear)
    {
        $students = Student::whereIn('id', $ids)->get();
        foreach ($students as $student) {
            $student->status = $status;
            
            if ($student->class_id) {
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        if (!$activeYear) {
            return redirect()->route('admin-students.index')->with('error', 'Tidak ada Tahun Pelajaran yang Aktif. Aktifkan tahun pelajaran terlebih dahulu untuk menambah siswa.');
        }

        $units = Unit::all();
        $classes = SchoolClass::where('academic_year_id', $activeYear->id)->get();
        return view('admin.students.create', compact('units', 'classes', 'activeYear'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        if (!$activeYear) {
            return redirect()->route('admin-students.index')->with('error', 'Penambahan siswa gagal: Tidak ada Tahun Pelajaran yang Aktif.');
        }

        $request->validate([
            // User Validation
            'email' => 'required|string|email|max:255|unique:user_siswa',
            'username' => 'nullable|string|max:255|unique:user_siswa',
            'password' => 'required|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Student Validation
            'nis' => 'required|string|unique:students',
            'nisn' => 'required|string|unique:students',
            'unit_id' => 'required|exists:units,id',
            'class_id' => 'required|exists:classes,id',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date_format:d/m/Y',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'required|string',
            'alamat_rt' => 'nullable|string|max:10',
            'alamat_rw' => 'nullable|string|max:10',
            'desa' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kota' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:20',
            'no_hp' => 'nullable|string|max:20',
            'nama_wali' => 'required|string|max:255',
            'no_hp_wali' => 'required|string|max:20',
            'status' => 'required|in:aktif,lulus,keluar,pindah,non-aktif',
        ]);

        DB::beginTransaction();

        try {
            // Create UserSiswa
            $userData = [
                'name' => $request->nama_lengkap, // Sync mapped to Student Name
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'plain_password' => $request->password,
            ];

            if ($request->hasFile('photo')) {
                $imageName = time().'.'.$request->photo->extension();
                
                $manager = new ImageManager(new Driver());
                $image = $manager->read($request->photo);
                $image->cover(354, 472);
                $image->save(public_path('photos/' . $imageName));
                
                $thumbPath = public_path('photos/thumb');
                if (!file_exists($thumbPath)) mkdir($thumbPath, 0755, true);
                $image->save($thumbPath . '/' . $imageName);

                $userData['photo'] = $imageName;
            }

            // Create UserSiswa
            $user = UserSiswa::create($userData);

            // Create Student linked to UserSiswa
            $student = Student::create([
                'user_siswa_id' => $user->id,
                'unit_id' => $request->unit_id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'is_boarding' => $request->is_boarding ?? 0,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
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

            // Sync Class History with Academic Year
            if ($request->class_id) {
                $class = SchoolClass::find($request->class_id);
                $yearId = $class ? $class->academic_year_id : $activeYear->id;
                $student->classes()->syncWithoutDetaching([$request->class_id => ['academic_year_id' => $yearId]]);
            }

            DB::commit();

            return redirect()->route('admin-students.index')
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
        $units = Unit::all();
        $classes = SchoolClass::all();
        return view('students.edit', compact('student', 'units', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user; // UserSiswa

        $request->validate([
            // User Validation
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('user_siswa')->ignore($user->id)],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('user_siswa')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Increased to 10MB
            
            // Student Validation
            'nis' => ['required', 'string', Rule::unique('students')->ignore($student->id)],
            'nisn' => ['required', 'string', Rule::unique('students')->ignore($student->id)],
            'unit_id' => 'required|exists:units,id',
            // 'class_id' => 'required|exists:classes,id',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date_format:d/m/Y',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'required|string',
            'alamat_rt' => 'nullable|string|max:3',
            'alamat_rw' => 'nullable|string|max:3',
            'desa' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kota' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:20',
            'no_hp' => 'nullable|string|max:20',
            'nama_wali' => 'required|string|max:255',
            'no_hp_wali' => 'required|string|max:20',
            'status' => 'required|in:aktif,lulus,keluar,pindah,non-aktif',
        ]);

        DB::beginTransaction();

        try {
            // Update UserSiswa
            $user->name = $request->nama_lengkap; // Sync with student name
            if ($request->filled('username')) {
                $user->username = $request->username;
            }
            $user->email = $request->email;
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
                $user->plain_password = $request->password;
            }

            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($user->photo) {
                    if (file_exists(public_path('photos/' . $user->photo))) {
                        unlink(public_path('photos/' . $user->photo));
                    }
                    if (file_exists(public_path('photos/thumb/' . $user->photo))) {
                        unlink(public_path('photos/thumb/' . $user->photo));
                    }
                }

                $file = $request->file('photo');
                $imageName = time() . '_' . $file->getClientOriginalName();
                
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);
                $image->cover(354, 472);
                $image->save(public_path('photos/' . $imageName));
                
                $thumbPath = public_path('photos/thumb');
                if (!file_exists($thumbPath)) mkdir($thumbPath, 0755, true);
                $image->save($thumbPath . '/' . $imageName);

                $user->photo = $imageName;
            }

            $user->save();

            // Update Student
            $student->update([
                'unit_id' => $request->unit_id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'is_boarding' => $request->is_boarding ?? 0,
                'tempat_lahir' => $request->tempat_lahir,
                'kota' => $request->kota,
                'kode_pos' => $request->kode_pos,
                'no_hp' => $request->no_hp,
                'tanggal_lahir' => $request->tanggal_lahir, // Model mutator handles d/m/Y -> Y-m-d
                'agama' => $request->agama,
                'alamat' => $request->alamat,
                'alamat_rt' => $request->alamat_rt,
                'alamat_rw' => $request->alamat_rw,
                'desa' => $request->desa,
                'kecamatan' => $request->kecamatan,
                'nama_wali' => $request->nama_wali,
                'no_hp_wali' => $request->no_hp_wali,
                'status' => $request->status,
            ]);

            // Sync Class History
            if ($request->class_id) {
                $class = SchoolClass::find($request->class_id);
                if ($class) {
                    $student->classes()->syncWithoutDetaching([$request->class_id => ['academic_year_id' => $class->academic_year_id]]);
                }
            }

            DB::commit();

            return redirect()->route('admin-students.edit', $student->id)
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
        $user = $student->user; // UserSiswa

        if ($user && $user->photo) {
            if (file_exists(public_path('photos/' . $user->photo))) {
                unlink(public_path('photos/' . $user->photo));
            }
            if (file_exists(public_path('photos/thumb/' . $user->photo))) {
                unlink(public_path('photos/thumb/' . $user->photo));
            }
        }

        // Deleting user_siswa will cascade delete student due to db constraints
        if ($user) {
            $user->delete();
        } else {
             // Fallback
             $student->delete();
        }

        return redirect()->route('admin-students.index')
            ->with('success', 'Student deleted successfully');
    }

    public function toggleStatus($id)
    {
        $student = Student::findOrFail($id);
        
        if ($student->status == 'aktif') {
            $student->status = 'non-aktif'; 
        } else {
            $student->status = 'aktif';
        }
        
        $student->save();

        return redirect()->back()->with('success', 'Student status updated successfully.');
    }

    public function activateAll()
    {
        Student::query()->update(['status' => 'aktif']);
        return redirect()->back()->with('success', 'All students activated successfully.');
    }

    public function deactivateAll()
    {
        Student::query()->update(['status' => 'non-aktif']);
        return redirect()->back()->with('success', 'All students deactivated successfully.');
    }

    public function resetPassword($id)
    {
        $student = Student::with('user')->findOrFail($id);
        $user = $student->user; // UserSiswa

        if ($user) {
            // Unlock account logic ONLY
            $user->login_attempts = 0;
            $user->locked_at = null;
            $user->save();
            return redirect()->back()->with('success', 'Akun berhasil dibuka kuncinya (Unlock Login). Password TIDAK berubah.');
        }

        return redirect()->back()->with('error', 'User akun tidak ditemukan.');
    }


    public function deletePhoto($id)
    {
        $student = Student::with('user')->findOrFail($id);
        $user = $student->user;

        if ($user && $user->photo) {
            if (file_exists(public_path('photos/' . $user->photo))) {
                unlink(public_path('photos/' . $user->photo));
            }
            if (file_exists(public_path('photos/thumb/' . $user->photo))) {
                unlink(public_path('photos/thumb/' . $user->photo));
            }
            $user->photo = null;
            $user->save();
            return redirect()->back()->with('success', 'Photo deleted successfully.');
        }

        return redirect()->back()->with('error', 'No photo to delete.');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_siswa.csv"',
        ];

        $columns = ['nis', 'nisn', 'nama', 'unit', 'username', 'password', 'jenis_kelamin (L/P)' ,'tempat_lahir', 'tanggal_lahir (YYYY-MM-DD)', 'nama_wali', 'no_hp_wali'];
        
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Example Row
            fputcsv($file, ['12345', '0012345678', 'Contoh Siswa', 'SMA', 'siswa123', 'password123', 'L', 'Jakarta', '2005-01-01', 'Budi Santoso', '08123456789']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        if (!$activeYear) {
            return redirect()->route('admin-students.index')->with('error', 'Import gagal: Tidak ada Tahun Pelajaran yang Aktif.');
        }

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
            return strtolower(trim(preg_replace('/[\x00-\x1F\x7F]/', '', $h)));
        }, $header);
        
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

        if ($idx_nis === false || $idx_nama === false || $idx_unit === false || $idx_user === false || $idx_pass === false) {
             return redirect()->back()->with('error', "Format header salah atau Delimiter tidak dikenali. Wajib ada kolom: NIS, Nama, Unit, Username, Password.");
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
            
            $nis      = trim($row[$idx_nis] ?? '');
            $nisn     = trim($row[$idx_nisn] ?? '');
            $nama     = trim($row[$idx_nama] ?? '');
            $unitInput= trim($row[$idx_unit] ?? '');
            $username = trim($row[$idx_user] ?? '');
            $password = trim($row[$idx_pass] ?? '');
            
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

            // 4. Duplicate Check (In DB)
            if (in_array($nis, $existingNIS)) {
                $errors[] = "Baris $rowNum: NIS '$nis' sudah terdaftar.";
                break;
            }
            if (in_array($username, $existingUsernames)) {
                 $errors[] = "Baris $rowNum: Username '$username' sudah terdaftar.";
                 break;
            }

            // 5. Duplicate Check (In File)
            if (in_array($nis, $fileNIS)) $errors[] = "Baris $rowNum: NIS '$nis' duplikat di file.";
            if (in_array($username, $fileUsernames)) $errors[] = "Baris $rowNum: Username '$username' duplikat di file.";

            $fileNIS[] = $nis;
            $fileUsernames[] = $username;

            $validRows[] = [
                'nis' => $nis,
                'nisn' => $nisn ?: '-',
                'nama' => $nama,
                'unit_id' => $finalUnitId,
                'username' => $username,
                'password' => $password,
                'gender' => $row[$idx_jk] ?? 'L',
                'birth_date' => $row[$idx_tgl] ?? null,
            ];
        }

        if (count($errors) > 0) {
            $msg = "Gagal Upload! Ditemukan " . count($errors) . " kesalahan:<br><ul>";
            foreach(array_slice($errors, 0, 10) as $err) $msg .= "<li>$err</li>";
            if(count($errors) > 10) $msg .= "<li>...dan " . (count($errors) - 10) . " lainnya.</li>";
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
                     if (str_contains($val, 'P') || str_contains($val, 'W') || str_contains($val, 'PEREMPUAN')) $jk = 'P';
                }

                // Parse Date
                $born = null;
                if (!empty($d['birth_date'])) {
                    try {
                        $born = \Carbon\Carbon::parse($d['birth_date'])->format('Y-m-d');
                    } catch (\Exception $e) {}
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
                    'tempat_lahir' => 'Unknown',
                    'tanggal_lahir' => $born,
                    'nama_wali' => 'Unknown',
                    'no_hp_wali' => '-',
                    'alamat' => '-',
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
}
