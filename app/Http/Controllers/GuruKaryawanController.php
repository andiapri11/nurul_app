<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GuruKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Index method moved to handle search and pagination
    
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_guru_karyawan.csv"',
        ];
        
        $columns = [
            'Nama Lengkap',
            'NIP', 
            'Email', 
            'Role (guru/karyawan/staff)', 
            'Username', 
            'Password', 
            'No. HP',
            'Unit ID'
        ];
        
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            // Example
            fputcsv($file, ['Budi Santoso, S.Pd', '198001012005011001', 'budi@sekolah.id', 'guru', 'budi80', '12345678', '08123456789', '1']);
            fputcsv($file, ['Siti Aminah, S.Kom', 'K001', 'siti@sekolah.id', 'karyawan', 'siti_tu', 'password', '08567891234', '2']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048'
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        // Strictly reject binary Excel files as per user request
        if (in_array($extension, ['xlsx', 'xls'])) {
             return back()->with('error', '<strong>Gagal!</strong> Format Excel (.xlsx/.xls) tidak didukung. Silakan buka file tersebut di Excel, lalu pilih "Save As" (Simpan Sebagai) dan pilih format <strong>CSV (Comma delimited) (*.csv)</strong> sebelum diupload.');
        }
        
        if ($extension !== 'csv' && $extension !== 'txt') {
             return back()->with('error', 'Format file tidak dikenali. Pastikan file berakhiran <strong>.csv</strong>');
        }

        // CSV Robust Logic
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        
        // Remove UTF-8 BOM if present
        $bom = pack('H*','EFBBBF');
        $content = preg_replace("/^$bom/", '', $content);
        
        $lines = preg_split('/\r\n|\r|\n/', $content);
        $lines = array_filter($lines, 'trim'); // Remove empty lines
        
        if (empty($lines)) {
             return redirect()->back()->with('error', 'File kosong atau isi tidak terbaca.');
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

        $header = array_shift($data);
        
        // Normalize headers logic
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

        $idx_name = $getIndex(['nama', 'name', 'lengkap']);
        $idx_nip  = $getIndex(['nip', 'nomor induk', 'nuptk']);
        $idx_email= $getIndex(['email', 'mail']);
        $idx_role = $getIndex(['role', 'peran', 'jabatan_utama', 'status_pegawai']);
        $idx_user = $getIndex(['username', 'user']);
        $idx_pass = $getIndex(['password', 'sandi']);
        $idx_phone= $getIndex(['phone', 'telp', 'hp', 'no. hp']);
        $idx_unit = $getIndex(['unit', 'unit_id']);
        
        // Checklist required columns
        $missing = [];
        if ($idx_name === false) $missing[] = "Nama Lengkap";
        if ($idx_email === false) $missing[] = "Email";
        if ($idx_role === false) $missing[] = "Role";
        if ($idx_user === false) $missing[] = "Username";
        if ($idx_pass === false) $missing[] = "Password";

        if (!empty($missing)) {
             return redirect()->back()->with('error', "<strong>Format Header Salah!</strong> Kolom berikut tidak ditemukan: <strong>" . implode(', ', $missing) . "</strong>.<br><br>Gunakan template CSV yang disediakan untuk memastikan header benar.");
        }

        // VALIDATION
        $errors = [];
        $validRows = [];
        $existingNIPs = User::whereNotNull('nip')->pluck('nip')->toArray();
        $existingEmails = User::pluck('email')->toArray();
        $existingUsernames = User::pluck('username')->toArray();
        
        $fileNIPs = [];
        $fileUsernames = [];
        $fileEmails = [];

        foreach ($data as $rowIndex => $row) {
            $rowNum = $rowIndex + 2;
            
            // Ensure row counts match header
            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            }

            $name      = trim($row[$idx_name] ?? '');
            $nip       = trim($row[$idx_nip] ?? '');
            $email     = trim($row[$idx_email] ?? '');
            $role      = strtolower(trim($row[$idx_role] ?? ''));
            $username  = trim($row[$idx_user] ?? '');
            $password  = trim($row[$idx_pass] ?? '');
            $phone     = trim($row[$idx_phone] ?? '');
            $unitInput = trim($row[$idx_unit] ?? '');

            if ($name === '' && $username === '') continue; // Skip empty rows

            // Required Validation
            if ($name === '' || $email === '' || $role === '' || $username === '' || $password === '') {
                $errors[] = "Baris $rowNum: Data utama (Nama, Email, Role, User, Pass) tidak boleh kosong.";
                continue;
            }

            // Unit Lookup
            $finalUnitId = null;
            if ($unitInput !== '') {
                if (is_numeric($unitInput)) {
                    $finalUnitId = $unitInput;
                } else {
                    $unitFound = \App\Models\Unit::where('name', 'LIKE', '%' . $unitInput . '%')
                        ->orWhere('code', 'LIKE', '%' . $unitInput . '%')
                        ->first();
                    if ($unitFound) {
                        $finalUnitId = $unitFound->id;
                    } else {
                        $errors[] = "Baris $rowNum: Unit '$unitInput' tidak terdaftar.";
                        continue;
                    }
                }
            }

            if ($finalUnitId && !\App\Models\Unit::find($finalUnitId)) {
                $errors[] = "Baris $rowNum: ID Unit '$finalUnitId' tidak ditemukan.";
                continue;
            }

            // Role Normalization
            if (str_contains($role, 'guru')) $role = 'guru';
            elseif (str_contains($role, 'karyawan')) $role = 'karyawan';
            elseif (str_contains($role, 'staff')) $role = 'staff';

            if (!in_array($role, ['guru', 'karyawan', 'staff'])) {
                $errors[] = "Baris $rowNum: Role '$role' tidak dikenali (gunakan: guru/karyawan/staff).";
                continue;
            }

            // Duplicate Checks (DB)
            if ($nip !== '' && in_array($nip, $existingNIPs)) {
                $errors[] = "Baris $rowNum: NIP '$nip' sudah ada di sistem.";
                continue;
            }
            if (in_array($email, $existingEmails)) {
                 $errors[] = "Baris $rowNum: Email '$email' sudah ada di sistem.";
                 continue;
            }
            if (in_array($username, $existingUsernames)) {
                 $errors[] = "Baris $rowNum: Username '$username' sudah ada di sistem.";
                 continue;
            }

            // Duplicate Checks (File)
            if ($nip !== '' && in_array($nip, $fileNIPs)) {
                $errors[] = "Baris $rowNum: NIP '$nip' tertulis ganda di file.";
                continue;
            }
            if (in_array($email, $fileEmails)) {
                $errors[] = "Baris $rowNum: Email '$email' tertulis ganda di file.";
                continue;
            }
            if (in_array($username, $fileUsernames)) {
                $errors[] = "Baris $rowNum: Username '$username' tertulis ganda di file.";
                continue;
            }
            
            if ($nip) $fileNIPs[] = $nip;
            $fileEmails[] = $email;
            $fileUsernames[] = $username;

            $validRows[] = [
                'name' => $name,
                'nip' => $nip ?: null,
                'email' => $email,
                'role' => $role,
                'username' => $username,
                'password' => $password,
                'phone' => $phone,
                'unit_id' => $finalUnitId,
            ];
        }

        if (count($errors) > 0) {
            $errHtml = "<ul>";
            foreach(array_slice($errors, 0, 10) as $e) $errHtml .= "<li>$e</li>";
            if (count($errors) > 10) $errHtml .= "<li>...dan " . (count($errors)-10) . " error lainnya.</li>";
            $errHtml .= "</ul>";
            return back()->with('error', '<strong>Import Gagal!</strong> Periksa kesalahan berikut:<br>' . $errHtml);
        }

        if (empty($validRows)) {
            return back()->with('error', 'Tidak ada data valid yang dapat diimport.');
        }

        // INSERT
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($validRows as $r) {
                User::create([
                    'name' => $r['name'],
                    'nip' => $r['nip'],
                    'email' => $r['email'],
                    'role' => $r['role'],
                    'username' => $r['username'],
                    'password' => Hash::make($r['password']),
                    'plain_password' => $r['password'],
                    'status' => 'aktif',
                    'unit_id' => $r['unit_id'],
                    'phone' => $r['phone'],
                ]);
            }
            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', 'Berhasil mengimport ' . count($validRows) . ' data Guru/Karyawan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Kesalahan Database: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatans = Jabatan::all();
        $units = \App\Models\Unit::all();
        return view('gurukaryawans.create', compact('jabatans', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:users,nip',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:guru,karyawan,staff',
            'jabatan_ids' => 'required|array',
            'jabatan_ids.*' => 'exists:jabatans,id',
            'unit_id' => 'nullable|exists:units,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'nip' => $request->nip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'plain_password' => $request->password, // Simpan text asli
            'role' => $request->role,
            'unit_id' => $request->unit_id,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone' => $request->phone,
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

            $data['photo'] = $imageName;
        }

        $user = User::create($data);
        $user->jabatans()->sync($request->jabatan_ids);

        return redirect()->route('gurukaryawans.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $gurukaryawan)
    {
        if (!in_array($gurukaryawan->role, ['guru', 'karyawan', 'staff'])) {
            abort(404);
        }
        
        // Eager load critical relationships
        // jabatanUnits relationship was added to User model, teachingAssignments too.
        // We must filter teachingAssignments by active academic year
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $activeYearId = $activeYear ? $activeYear->id : null;

        $gurukaryawan->load(['jabatans', 'teachingAssignments' => function($q) use ($activeYearId) {
            if ($activeYearId) {
                $q->where('academic_year_id', $activeYearId)
                  ->orWhereNull('academic_year_id'); 
            }
        }, 'teachingAssignments.schoolClass', 'teachingAssignments.subject', 'jabatanUnits' => function($q) use ($activeYearId) {
            if ($activeYearId) {
                $q->where('academic_year_id', $activeYearId)
                  ->orWhereNull('academic_year_id');
            }
        }]); 
        
        $units = \App\Models\Unit::all();
        $jabatans = \App\Models\Jabatan::all();
        $jabatansByUnit = $jabatans->groupBy('unit_id');

        
        // Preload subjects and classes grouped by unit for robust client-side filtering
        // This solves the "dropdown not showing" issue by removing AJAX dependency
        $allSubjects = \App\Models\Subject::select('id', 'name', 'code', 'unit_id')->orderBy('name')->get()->groupBy('unit_id');
        $allClasses = \App\Models\SchoolClass::select('id', 'name', 'unit_id')->orderBy('name')->get()->groupBy('unit_id');

        return view('gurukaryawans.edit', compact('gurukaryawan', 'jabatans', 'units', 'allSubjects', 'allClasses', 'jabatansByUnit'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $gurukaryawan)
    {
        if (!in_array($gurukaryawan->role, ['guru', 'karyawan', 'staff'])) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required',
            // Flexible validation for assignments
            'assignments' => 'nullable|array',
            'jabatan_units' => 'nullable|array',
        ]);

        // 1. Update Basic Info
        $gurukaryawan->name = $request->name;
        if($request->has('nip')) $gurukaryawan->nip = $request->nip;
        if($request->has('email')) $gurukaryawan->email = $request->email;
        if($request->has('role')) $gurukaryawan->role = $request->role;
        if($request->has('unit_id')) $gurukaryawan->unit_id = $request->unit_id;

        $gurukaryawan->birth_place = $request->birth_place;
        $gurukaryawan->birth_date = $request->birth_date;
        $gurukaryawan->gender = $request->gender;
        $gurukaryawan->address = $request->address;
        $gurukaryawan->phone = $request->phone;
        
        // Update Username (jika ada input)
        if($request->has('username')) {
            $request->validate([
                'username' => 'required|string|max:255|unique:users,username,' . $gurukaryawan->id
            ]);
            $gurukaryawan->username = $request->username;
        }
        
        // Handle password only if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6'
            ]);
            $gurukaryawan->password = Hash::make($request->password);
            $gurukaryawan->plain_password = $request->password; // Simpan versi teks asli
        }
        
        // Photo handling
        // Photo handling
        if ($request->hasFile('photo')) {
            if ($gurukaryawan->photo && file_exists(public_path('photos/' . $gurukaryawan->photo))) {
                unlink(public_path('photos/' . $gurukaryawan->photo));
            }
            if ($gurukaryawan->photo && file_exists(public_path('photos/thumb/' . $gurukaryawan->photo))) {
                unlink(public_path('photos/thumb/' . $gurukaryawan->photo));
            }

            $imageName = time().'.'.$request->photo->extension();
            
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->photo);
            $image->cover(354, 472);
            $image->save(public_path('photos/' . $imageName));

            $thumbPath = public_path('photos/thumb');
            if (!file_exists($thumbPath)) mkdir($thumbPath, 0755, true);
            $image->save($thumbPath . '/' . $imageName);

            $gurukaryawan->photo = $imageName;
        }

        $gurukaryawan->save();

        // 2. Handle Jabatan Units (Pivot Table Baru)
        // Format input: jabatan_units[index][jabatan_id] & jabatan_units[index][unit_id]
        if ($request->has('jabatan_units')) {
            // Get Active Academic Year
            $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
            $activeYearId = $activeYear ? $activeYear->id : null;

            // Strategy: Force Sync for current year.
            if ($activeYearId) {
                // Remove existing for this user in this year
                \App\Models\UserJabatanUnit::where('user_id', $gurukaryawan->id)
                    ->where('academic_year_id', $activeYearId)
                    ->delete();
                // Also remove NULL year ones to clean legacy
                \App\Models\UserJabatanUnit::where('user_id', $gurukaryawan->id)
                    ->whereNull('academic_year_id')
                    ->delete();
            } else {
                 \App\Models\UserJabatanUnit::where('user_id', $gurukaryawan->id)
                    ->whereNull('academic_year_id')
                    ->delete();
            }
            
            foreach ($request->jabatan_units as $ju) {
                if (!empty($ju['jabatan_id']) && !empty($ju['unit_id'])) {
                    // --- ENFORCE STRUCTURAL UNIQUE RULE ---
                    $jabatan = \App\Models\Jabatan::find($ju['jabatan_id']);
                    if ($jabatan && $jabatan->tipe === 'struktural') {
                        $occupied = \App\Models\UserJabatanUnit::where('jabatan_id', $ju['jabatan_id'])
                            ->where('unit_id', $ju['unit_id'])
                            ->where('academic_year_id', $activeYearId)
                            ->where('user_id', '!=', $gurukaryawan->id)
                            ->with('user')
                            ->first();

                        if ($occupied) {
                            $holderName = $occupied->user->name ?? 'User Lain';
                            $unitName = \App\Models\Unit::find($ju['unit_id'])->name ?? 'Unit tsb';
                            return redirect()->back()
                                ->withInput()
                                ->with('error', "GAGAL SIMPAN: Jabatan Struktural '{$jabatan->nama_jabatan}' di {$unitName} sudah diisi oleh {$holderName}. Jabatan struktural hanya boleh diisi oleh 1 orang!");
                        }
                    }

                    // Check logic old way to prevent index errors if index still exists
                    $exists = \App\Models\UserJabatanUnit::where('user_id', $gurukaryawan->id)
                        ->where('jabatan_id', $ju['jabatan_id'])
                        ->where('unit_id', $ju['unit_id'])
                        ->exists();

                    if (!$exists) {
                         \App\Models\UserJabatanUnit::create([
                            'user_id' => $gurukaryawan->id,
                            'jabatan_id' => $ju['jabatan_id'],
                            'unit_id' => $ju['unit_id'],
                            'academic_year_id' => $activeYearId,
                        ]);
                    } else {
                        // Just update the existing one to current year if it was dangling
                        \App\Models\UserJabatanUnit::where('user_id', $gurukaryawan->id)
                             ->where('jabatan_id', $ju['jabatan_id'])
                             ->where('unit_id', $ju['unit_id'])
                             ->update(['academic_year_id' => $activeYearId]);
                    }
                }
            }
        }
        
        // Legacy Support: Also update the old 'jabatans' pivot for backward compatibility
        // Extract unique jabatan IDs from the new structure
        if ($request->has('jabatan_units')) {
            $jabatanIds = collect($request->jabatan_units)->pluck('jabatan_id')->unique()->filter()->toArray();
            $gurukaryawan->jabatans()->sync($jabatanIds);
        }

        // 3. Handle Teaching Assignments (Tugas Mengajar)
        // VALIDASI KONFLIK DULU (Cek apakah mapel di kelas tsb sudah ada gurunya)
        if ($request->has('assignments') && is_array($request->assignments)) {
            foreach ($request->assignments as $assignment) {
                if (!empty($assignment['subject_id']) && !empty($assignment['class_id'])) {
                    
                    $conflict = \App\Models\TeachingAssignment::where('subject_id', $assignment['subject_id'])
                        ->where('class_id', $assignment['class_id'])
                        ->where('user_id', '!=', $gurukaryawan->id) // Abaikan diri sendiri
                        ->with(['user', 'subject', 'schoolClass'])
                        ->first();

                    if ($conflict) {
                        $mapel = $conflict->subject->name ?? 'Mapel';
                        $kelas = $conflict->schoolClass->name ?? 'Kelas';
                        $guruLain = $conflict->user->name ?? 'Guru Lain';

                        return redirect()->back()
                            ->withInput()
                            ->with('error', "GAGAL SIMPAN: $mapel di $kelas sudah diajar oleh $guruLain. Satu mapel di kelas hanya boleh 1 guru!");
                    }
                }
            }
        }

        // JIKA AMAN, BARU EKSEKUSI SIMPAN
        
        // Strategy: Only replace assignments for the CURRENT Active Year.
        // If academic_year_id column exists, we can scope it.
        // We just added it.
        
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $activeYearId = $activeYear ? $activeYear->id : null;

        if ($activeYearId) {
             // Delete existing for this user in this year
             \App\Models\TeachingAssignment::where('user_id', $gurukaryawan->id)
                ->where('academic_year_id', $activeYearId)
                ->delete();
                
             // ALSO delete legacy records (NULL year) because they "become" the current year
             // If we don't delete them, they persist as ghosts if this edit was meant to replace them.
             // Assumption: If I am editing in active year X, I want to fully define the state for year X.
             // Legacy records (created before this feature) effectively belong to the "current" context.
             \App\Models\TeachingAssignment::where('user_id', $gurukaryawan->id)
                ->whereNull('academic_year_id')
                ->delete();
        } else {
             // Fallback for transition compatibility?
             // Or maybe just delete where academic_year_id is NULL for now?
             \App\Models\TeachingAssignment::where('user_id', $gurukaryawan->id)
                ->whereNull('academic_year_id')
                ->delete();
        }

        if ($request->has('assignments') && is_array($request->assignments)) {
            // FIX: Filter duplikat
            $uniqueAssignments = collect($request->assignments)
                ->filter(function($item) {
                     return !empty($item['subject_id']) && !empty($item['class_id']);
                })
                ->unique(function ($item) {
                    return $item['subject_id'] . '-' . $item['class_id'];
                });

            foreach ($uniqueAssignments as $assignment) {
                \App\Models\TeachingAssignment::create([
                    'user_id' => $gurukaryawan->id,
                    'subject_id' => $assignment['subject_id'],
                    'class_id' => $assignment['class_id'],
                    'academic_year_id' => $activeYearId,
                ]);
            }
        }

        return redirect()->route('gurukaryawans.index', $request->get('return_params', []))
            ->with('success', 'Data Guru berhasil diperbarui (Jabatan & Mapel tersimpan).');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $gurukaryawan)
    {
        if (!in_array($gurukaryawan->role, ['guru', 'karyawan', 'staff'])) {
            abort(404);
        }

        if ($gurukaryawan->photo) {
            if (file_exists(public_path('photos/' . $gurukaryawan->photo))) {
                unlink(public_path('photos/' . $gurukaryawan->photo));
            }
            if (file_exists(public_path('photos/thumb/' . $gurukaryawan->photo))) {
                unlink(public_path('photos/thumb/' . $gurukaryawan->photo));
            }
        }

        $gurukaryawan->delete();

        return redirect()->route('gurukaryawans.index')
            ->with('success', 'User deleted successfully');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        if (!in_array($user->role, ['guru', 'karyawan', 'staff'])) {
            return redirect()->back()->with('error', 'Cannot change status for this user type.');
        }

        if ($user->status == 'aktif') {
            $user->status = 'non-aktif'; 
        } else {
            $user->status = 'aktif';
        }
        
        $user->save();

        return redirect()->back()->with('success', 'User status updated successfully.');
    }

    public function activateAll()
    {
        User::whereIn('role', ['guru', 'karyawan', 'staff'])->update(['status' => 'aktif']);
        return redirect()->back()->with('success', 'All status activated successfully.');
    }

    public function deactivateAll()
    {
        User::whereIn('role', ['guru', 'karyawan', 'staff'])->update(['status' => 'non-aktif']);
        return redirect()->back()->with('success', 'All status deactivated successfully.');
    }

    private function getAllowedUnits()
    {
        $user = auth()->user();
        if (in_array($user->role, ['administrator', 'admin', 'direktur'])) {
            return \App\Models\Unit::all();
        }
        return $user->getLearningManagementUnits(); // Use existing helper or getManajemenUnits()
    }
    
    // ...
    
    public function index(Request $request)
    {
        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();

        // Load relasi lengkap: JabatanUnits, TeachingAssignments, dan Kelas Wali
        // Load relasi lengkap: JabatanUnits, TeachingAssignments, dan Kelas Wali
        // Filter teachingAssignments by active academic year so list reflects current reality
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        $activeYear = $academicYears->where('status', 'active')->first();
        
        // Use requested academic year OR active year OR null
        $filterYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);

        $query = User::whereIn('role', ['guru', 'karyawan', 'staff'])
            ->with(['jabatanUnits' => function($q) use ($filterYearId) {
                 if ($filterYearId) {
                     $q->where('academic_year_id', $filterYearId)
                       ->orWhereNull('academic_year_id');
                 }
            }, 'jabatanUnits.jabatan', 'jabatanUnits.unit', 
                'teachingAssignments' => function($q) use ($filterYearId) {
                    if ($filterYearId) {
                         $q->where('academic_year_id', $filterYearId)
                           ->orWhereNull('academic_year_id');
                    }
                }, 
                'teachingAssignments.subject', 'teachingAssignments.schoolClass', 'waliKelasOf' => function($q) use ($filterYearId) {
                    if ($filterYearId) {
                        $q->where('academic_year_id', $filterYearId);
                    }
                }]);

        // AUTHORIZATION FILTER
        if (auth()->user()->role !== 'administrator' && auth()->user()->role !== 'direktur') {
             $query->where(function($q) use ($allowedIds) {
                 // 1. Home Base Unit matches
                 $q->whereIn('unit_id', $allowedIds)
                   // 2. OR Has Jabatan in allowed Unit
                   ->orWhereHas('jabatanUnits', function($sq) use ($allowedIds) {
                       $sq->whereIn('unit_id', $allowedIds);
                   })
                   // 3. OR Teaches in allowed Unit
                   ->orWhereHas('teachingAssignments.schoolClass', function($sq) use ($allowedIds) {
                       $sq->whereIn('unit_id', $allowedIds);
                   });
             });
        }

        if ($request->has('unit_id') && $request->unit_id != '') {
            $unitId = $request->unit_id;
            // Validate if unit is allowed for this user
            if (in_array($unitId, $allowedIds)) {
                $query->where(function($q) use ($unitId) {
                     $q->where('unit_id', $unitId)
                       ->orWhereHas('jabatanUnits', function($sq) use ($unitId) {
                           $sq->where('unit_id', $unitId);
                       })
                       ->orWhereHas('teachingAssignments.schoolClass', function($sq) use ($unitId) {
                           $sq->where('unit_id', $unitId);
                       });
                 });
            }
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        $gurukaryawans = $query->paginate(12);
        return view('gurukaryawans.index', compact('gurukaryawans', 'allowedUnits', 'academicYears', 'filterYearId'));
    }

    // ... (rest of controller) ...

    public function userIndex(Request $request)
    {
        $search = $request->get('search');
        $unitId = $request->get('unit_id');
        $academicYearId = $request->get('academic_year_id');

        $query = User::whereIn('role', ['guru', 'karyawan', 'staff'])
                     ->with(['jabatans', 'jabatanUnits.unit', 'jabatanUnits.academicYear', 'unit']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($unitId) {
            $query->where(function($q) use ($unitId) {
                $q->where('unit_id', $unitId)
                  ->orWhereHas('jabatanUnits', function($sq) use ($unitId) {
                      $sq->where('unit_id', $unitId);
                  });
            });
        }

        if ($academicYearId) {
            $query->whereHas('jabatanUnits', function($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            });
        }

        $gurukaryawans = $query->latest()->paginate(12);
        
        $units = \App\Models\Unit::all();
        $academicYears = \App\Models\AcademicYear::orderBy('status', 'asc')->orderBy('start_year', 'desc')->get();

        return view('gurukaryawans.user_index', compact('gurukaryawans', 'units', 'academicYears', 'unitId', 'academicYearId'));
    }

    /**
     * Copy teaching assignments and job positions from one academic year to another.
     */
    public function copyData(Request $request)
    {
        $request->validate([
            'from_academic_year_id' => 'required|exists:academic_years,id',
            'to_academic_year_id' => 'required|exists:academic_years,id|different:from_academic_year_id',
        ]);

        $fromYearId = $request->from_academic_year_id;
        $toYearId = $request->to_academic_year_id;

        $fromYear = \App\Models\AcademicYear::find($fromYearId);
        $toYear = \App\Models\AcademicYear::find($toYearId);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Copy Job Positions (UserJabatanUnit)
            $positions = \App\Models\UserJabatanUnit::where('academic_year_id', $fromYearId)->get();
            $copiedPositionsCount = 0;
            $skippedPositionsConflicts = 0;

            foreach ($positions as $pos) {
                // Check if this position category (jabatan_id + unit_id) is structural and already filled in toYear
                $jabatan = \App\Models\Jabatan::find($pos->jabatan_id);
                if ($jabatan && $jabatan->tipe === 'struktural') {
                    $existsInToYear = \App\Models\UserJabatanUnit::where('jabatan_id', $pos->jabatan_id)
                        ->where('unit_id', $pos->unit_id)
                        ->where('academic_year_id', $toYearId)
                        ->exists();
                    if ($existsInToYear) {
                        $skippedPositionsConflicts++;
                        continue;
                    }
                }

                // Check if exact record exists
                $exists = \App\Models\UserJabatanUnit::where('user_id', $pos->user_id)
                    ->where('jabatan_id', $pos->jabatan_id)
                    ->where('unit_id', $pos->unit_id)
                    ->where('academic_year_id', $toYearId)
                    ->exists();

                if (!$exists) {
                    \App\Models\UserJabatanUnit::create([
                        'user_id' => $pos->user_id,
                        'jabatan_id' => $pos->jabatan_id,
                        'unit_id' => $pos->unit_id,
                        'academic_year_id' => $toYearId,
                    ]);
                    $copiedPositionsCount++;
                }
            }

            // 2. Copy Teaching Assignments (TeachingAssignment)
            $assignments = \App\Models\TeachingAssignment::where('academic_year_id', $fromYearId)->get();
            $copiedAssignmentsCount = 0;
            $skippedAssignmentsConflicts = 0;

            foreach ($assignments as $asgn) {
                // Check if subject in this class is already assigned to someone else in toYear
                $conflict = \App\Models\TeachingAssignment::where('subject_id', $asgn->subject_id)
                    ->where('class_id', $asgn->class_id)
                    ->where('academic_year_id', $toYearId)
                    ->where('user_id', '!=', $asgn->user_id)
                    ->exists();

                if ($conflict) {
                    $skippedAssignmentsConflicts++;
                    continue;
                }

                // Check if exact record exists
                $exists = \App\Models\TeachingAssignment::where('user_id', $asgn->user_id)
                    ->where('subject_id', $asgn->subject_id)
                    ->where('class_id', $asgn->class_id)
                    ->where('academic_year_id', $toYearId)
                    ->exists();

                if (!$exists) {
                    \App\Models\TeachingAssignment::create([
                        'user_id' => $asgn->user_id,
                        'subject_id' => $asgn->subject_id,
                        'class_id' => $asgn->class_id,
                        'academic_year_id' => $toYearId,
                    ]);
                    $copiedAssignmentsCount++;
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            $msg = "Berhasil menyalin data dari {$fromYear->name} ke {$toYear->name}.<br>" .
                   "- Jabatan disalin: {$copiedPositionsCount}<br>" .
                   "- Mapel disalin: {$copiedAssignmentsCount}";
            
            if ($skippedPositionsConflicts > 0 || $skippedAssignmentsConflicts > 0) {
                $msg .= "<br><br>Catatan:<br>" .
                        "- {$skippedPositionsConflicts} jabatan struktural dilewati karena sudah ada pengisinya.<br>" .
                        "- {$skippedAssignmentsConflicts} mapel dilewati karena sudah ada pengajar lain.";
            }

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menyalin data: ' . $e->getMessage());
        }
    }
}
