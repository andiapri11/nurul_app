<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    
    // Per-request cache for expensive role/jabatan checks
    protected $checkCache = [];

    /**
     * Check if user has specific role
     * Replaces Spatie hasRole for simple string based role column
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    const ROLES = [
        'administrator',
        'direktur',
        'kepala sekolah',
        'wakil kepala sekolah',
        'staff',
        'guru',
        'karyawan',
        'siswa',
        'mading', // Use for Display/Kiosk mode
        'admin_keuangan',
        'kepala_keuangan',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nip',
        'email',
        'username',
        'password',
        'plain_password',
        'role',
        'photo',
        'jabatan_id',
        'status',
        'unit_id',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'phone',
        'security_pin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'plain_password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function jabatans()
    {
        // Many-to-Many relationship via pivot table user_jabatan_units
        return $this->belongsToMany(
            Jabatan::class,
            'user_jabatan_units',
            'user_id',
            'jabatan_id'
        )->withPivot('unit_id')->withTimestamps();
    }

    // Accessor for backward compatibility (returns first jabatan)
    public function getJabatanAttribute()
    {
        return $this->jabatans->first();
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teachingAssignments()
    {
        return $this->hasMany(\App\Models\TeachingAssignment::class);
    }
    
    // Helper to get assigned subjects (unique)
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teaching_assignments', 'user_id', 'subject_id')->distinct();
    }

    /**
     * Relasi Utama: Jabatan Guru beserta Unitnya
     * Menggunakan tabel pivot 'user_jabatan_units'
     */
    public function jabatanUnits()
    {
        return $this->hasMany(UserJabatanUnit::class);
    }

    public function getUnitAttribute()
    {
        if (isset($this->checkCache['unit_accessor'])) return $this->checkCache['unit_accessor'];

        // If relationship is already loaded, use collection to avoid query
        if ($this->relationLoaded('jabatanUnits')) {
             $firstAssignment = $this->jabatanUnits->first();
        } else {
             $firstAssignment = $this->jabatanUnits()->first();
        }
        
        return $this->checkCache['unit_accessor'] = ($firstAssignment ? $firstAssignment->unit : null);
    }
    
    // Explicit relation if needed for eager loading, though logic is complex now.
    // Better to rely on jabatanUnits.unit
    
    /**
     * Relasi Guru sebagai Wali Kelas
     */
    public function waliKelasOf()
    {
        return $this->hasOne(SchoolClass::class, 'teacher_id');
    }

    /**
     * Cek apakah user punya jabatan tertentu (Case Insensitive & Partial Match)
     */
    public function hasJabatan($keyword)
    {
        $cacheKey = 'hasJabatan_' . strtolower($keyword);
        if (isset($this->checkCache[$cacheKey])) return $this->checkCache[$cacheKey];

        return $this->checkCache[$cacheKey] = $this->jabatanUnits()->whereHas('jabatan', function($q) use ($keyword) {
            $q->where('nama_jabatan', 'LIKE', '%' . $keyword . '%');
        })->exists();
    }

    /**
     * Cek apakah user termasuk dalam Manajemen Sekolah (Kepala Sekolah atau Wakasek)
     */
    public function isManajemenSekolah()
    {
        if (isset($this->checkCache['isManajemenSekolah'])) return $this->checkCache['isManajemenSekolah'];

        if (in_array($this->role, ['administrator', 'direktur'])) {
            return $this->checkCache['isManajemenSekolah'] = true;
        }

        return $this->checkCache['isManajemenSekolah'] = $this->jabatanUnits()->whereHas('jabatan', function($q) {
            $q->whereIn('kode_jabatan', [
                'kepala_sekolah',
                'wakil_kurikulum', 
                'wakil_kesiswaan', 
                'wakil_sarana_prasarana', 
                'wakil_humas'
            ])
            ->orWhere('nama_jabatan', 'LIKE', '%Kepala Sekolah%')
            ->orWhere('nama_jabatan', 'LIKE', '%Wakil Kepala Sekolah%')
            ->orWhere('nama_jabatan', 'LIKE', '%Kurikulum%')
            ->orWhere('nama_jabatan', 'LIKE', '%Kesiswaan%')
            ->orWhere('nama_jabatan', 'LIKE', '%Sarana%')
            ->orWhere('nama_jabatan', 'LIKE', '%Sarpras%');
        })->exists();
    }

    /**
     * Cek apakah user adalah Kepala Sekolah (Strict)
     */
    public function isKepalaSekolah()
    {
        if (isset($this->checkCache['isKepalaSekolah'])) return $this->checkCache['isKepalaSekolah'];

        if (in_array($this->role, ['administrator', 'direktur'])) {
            return $this->checkCache['isKepalaSekolah'] = true;
        }

        return $this->checkCache['isKepalaSekolah'] = $this->jabatanUnits()->whereHas('jabatan', function($q) {
            $q->where('kode_jabatan', 'kepala_sekolah')
              ->orWhere(function($sq) {
                  $sq->where('nama_jabatan', 'LIKE', '%Kepala Sekolah%')
                     ->where('nama_jabatan', 'NOT LIKE', '%Wakil%');
              });
        })->exists();
    }

    public function isDirektur()
    {
        return $this->role === 'direktur' || $this->role === 'administrator';
    }

    public function isSarpras()
    {
        if (isset($this->checkCache['isSarpras'])) return $this->checkCache['isSarpras'];

        if ($this->role === 'administrator' || $this->role === 'direktur') {
            return $this->checkCache['isSarpras'] = true;
        }

        return $this->checkCache['isSarpras'] = $this->jabatanUnits()->whereHas('jabatan', function($q) {
            $q->whereIn('kode_jabatan', ['wakil_sarana_prasarana', 'sarpras'])
              ->orWhere('nama_jabatan', 'LIKE', '%Sarana%')
              ->orWhere('nama_jabatan', 'LIKE', '%Sarpras%');
        })->exists();
    }

    public function isKesiswaan()
    {
        if (isset($this->checkCache['isKesiswaan'])) return $this->checkCache['isKesiswaan'];

        if ($this->role === 'administrator' || $this->role === 'direktur') {
            return $this->checkCache['isKesiswaan'] = true;
        }

        return $this->checkCache['isKesiswaan'] = $this->jabatanUnits()->whereHas('jabatan', function($q) {
            $q->whereIn('kode_jabatan', ['wakil_kesiswaan', 'kesiswaan'])
              ->orWhere('nama_jabatan', 'LIKE', '%Kesiswaan%');
        })->exists();
    }

    /**
     * Ambil Unit dimana user memiliki jabatanManajemen
     */
    public function getManajemenUnits()
    {
        if (isset($this->checkCache['manajemenUnits'])) return $this->checkCache['manajemenUnits'];

        if (in_array($this->role, ['administrator', 'direktur'])) {
            return $this->checkCache['manajemenUnits'] = \App\Models\Unit::all();
        }

        return $this->checkCache['manajemenUnits'] = \App\Models\Unit::whereIn('id', function($query) {
            $query->select('user_jabatan_units.unit_id')
                  ->from('user_jabatan_units')
                  ->where('user_id', $this->id)
                  ->join('jabatans', 'user_jabatan_units.jabatan_id', '=', 'jabatans.id')
                  ->where(function($q) {
                      $q->whereIn('jabatans.kode_jabatan', [
                            'kepala_sekolah',
                            'wakil_kurikulum', 
                            'wakil_kesiswaan', 
                            'wakil_sarana_prasarana', 
                            'wakil_humas'
                        ])
                        ->orWhere('jabatans.nama_jabatan', 'LIKE', '%Kepala Sekolah%')
                        ->orWhere('jabatans.nama_jabatan', 'LIKE', '%Wakil Kepala Sekolah%');
                  });
        })->get();
    }

    /**
     * Get Units where user is Kurikulum or Kepala Sekolah (for Subject/Schedule Management)
     */
    public function getLearningManagementUnits()
    {
        if (isset($this->checkCache['learningManagementUnits'])) return $this->checkCache['learningManagementUnits'];

        if (in_array($this->role, ['administrator', 'direktur'])) {
            return $this->checkCache['learningManagementUnits'] = \App\Models\Unit::all();
        }

        $units = \App\Models\Unit::whereIn('id', function($query) {
            $query->select('user_jabatan_units.unit_id')
                  ->from('user_jabatan_units')
                  ->where('user_id', $this->id)
                  ->join('jabatans', 'user_jabatan_units.jabatan_id', '=', 'jabatans.id')
                  ->where(function($q) {
                      $q->whereIn('jabatans.kode_jabatan', ['kepala_sekolah', 'wakil_kurikulum'])
                        ->orWhere('jabatans.nama_jabatan', 'LIKE', '%Kepala Sekolah%')
                        ->orWhere('jabatans.nama_jabatan', 'LIKE', '%Kurikulum%');
                  });
        })->get();

        // Fallback: If legacy KS/Kurikulum matches home unit
        if ($this->unit_id && ($this->isKurikulum() || $this->hasJabatan('Kepala Sekolah'))) {
             if (!$units->contains('id', $this->unit_id)) {
                 $homeUnit = \App\Models\Unit::find($this->unit_id);
                 if ($homeUnit) $units->push($homeUnit);
             }
        }
        
        return $this->checkCache['learningManagementUnits'] = $units;
    }

    /**
     * Get Units where user is Wakil Kesiswaan or Kepala Sekolah
     */
    public function getKesiswaanUnits()
    {
        if (isset($this->checkCache['kesiswaanUnits'])) return $this->checkCache['kesiswaanUnits'];

        if (in_array($this->role, ['administrator', 'direktur'])) {
            return $this->checkCache['kesiswaanUnits'] = \App\Models\Unit::all();
        }

        $units = \App\Models\Unit::whereIn('id', function($query) {
            $query->select('user_jabatan_units.unit_id')
                  ->from('user_jabatan_units')
                  ->where('user_id', $this->id)
                  ->join('jabatans', 'user_jabatan_units.jabatan_id', '=', 'jabatans.id')
                  ->where(function($q) {
                      $q->whereIn('jabatans.kode_jabatan', ['kepala_sekolah', 'wakil_kesiswaan'])
                        ->orWhere('jabatans.nama_jabatan', 'LIKE', '%Kepala Sekolah%')
                        ->orWhere('jabatans.nama_jabatan', 'LIKE', '%Kesiswaan%');
                  });
        })->get();

        // Fallback for home unit legacy
        if ($this->unit_id && ($this->hasJabatan('Kesiswaan') || $this->hasJabatan('Kepala Sekolah'))) {
             if (!$units->contains('id', $this->unit_id)) {
                 $homeUnit = \App\Models\Unit::find($this->unit_id);
                 if ($homeUnit) $units->push($homeUnit);
             }
        }
        
        return $this->checkCache['kesiswaanUnits'] = $units;
    }

    /**
     * Get Units where user is Wakil Sarpras or Kepala Sekolah
     */
    public function getSarprasUnits()
    {
        if (isset($this->checkCache['sarprasUnits'])) return $this->checkCache['sarprasUnits'];

        if (in_array($this->role, ['administrator', 'direktur'])) {
            return $this->checkCache['sarprasUnits'] = \App\Models\Unit::all();
        }

        $units = \App\Models\Unit::whereIn('id', function($query) {
            $query->select('user_jabatan_units.unit_id')
                  ->from('user_jabatan_units')
                  ->where('user_id', $this->id)
                  ->join('jabatans', 'user_jabatan_units.jabatan_id', '=', 'jabatans.id')
                  ->where(function($q) {
                      $q->whereIn('jabatans.kode_jabatan', ['kepala_sekolah', 'wakil_sarana_prasarana', 'sarpras'])
                        ->orWhere('jabatans.nama_jabatan', 'LIKE', '%Kepala Sekolah%')
                        ->orWhere('jabatans.nama_jabatan', 'LIKE', '%Sarana%')
                        ->orWhere('jabatans.nama_jabatan', 'LIKE', '%Sarpras%');
                  });
        })->get();

        // Fallback for home unit legacy
        if ($this->unit_id && ($this->isSarpras() || $this->isKepalaSekolah())) {
             if (!$units->contains('id', $this->unit_id)) {
                 $homeUnit = \App\Models\Unit::find($this->unit_id);
                 if ($homeUnit) $units->push($homeUnit);
             }
        }
        
        return $this->checkCache['sarprasUnits'] = $units;
    }

    /**
     * Helper khusus cek Kurikulum
     */
    public function getKurikulumUnits()
    {
        if ($this->role === 'administrator' || $this->role === 'direktur') {
            return \App\Models\Unit::all();
        }

        // 1. Get units from Pivot
        $units = \App\Models\Unit::whereIn('id', function($query) {
            $query->select('user_jabatan_units.unit_id')
                  ->from('user_jabatan_units')
                  ->where('user_id', $this->id)
                  ->join('jabatans', 'user_jabatan_units.jabatan_id', '=', 'jabatans.id')
                  ->where(function($q) {
                      $q->where('jabatans.kode_jabatan', 'wakil_kurikulum')
                        ->orWhere('jabatans.nama_jabatan', 'Wakil Kurikulum')
                        ->orWhere('jabatans.nama_jabatan', 'LIKE', '%Kurikulum%');
                  });
        })->get();

        // 2. Fallback: Check Legacy/Primary Jabatan
        if ($this->jabatan_id) {
            $legacyJabatan = \App\Models\Jabatan::find($this->jabatan_id);
            if ($legacyJabatan) {
                 $isKurikulumLegacy = ($legacyJabatan->kode_jabatan === 'wakil_kurikulum') || 
                                      str_contains($legacyJabatan->nama_jabatan, 'Kurikulum');
                 
                 if ($isKurikulumLegacy && $this->unit_id) {
                     if (!$units->contains('id', $this->unit_id)) {
                         $homeUnit = \App\Models\Unit::find($this->unit_id);
                         if ($homeUnit) $units->push($homeUnit);
                     }
                 }
            }
        }

        // 3. Fallback: If pivot verification existed but unit was missing
        if ($this->unit_id && $this->isKurikulum()) {
            if (!$units->contains('id', $this->unit_id)) {
                $homeUnit = \App\Models\Unit::find($this->unit_id);
                if ($homeUnit) {
                    $units->push($homeUnit);
                }
            }
        }
        
        return $units;
    }

    public function isKurikulum()
    {
        if (isset($this->checkCache['isKurikulum'])) return $this->checkCache['isKurikulum'];

        if ($this->role === 'administrator' || $this->role === 'direktur') {
            return $this->checkCache['isKurikulum'] = true;
        }
        
        // Check Pivot
        $pivotCheck = $this->jabatanUnits()->whereHas('jabatan', function($q) {
                   $q->where('kode_jabatan', 'wakil_kurikulum')
                     ->orWhere('nama_jabatan', 'LIKE', '%Kurikulum%');
               })->exists();
               
        if ($pivotCheck) return $this->checkCache['isKurikulum'] = true;
        
        // Check Legacy
        if ($this->jabatan_id) {
            $jabatan = \App\Models\Jabatan::find($this->jabatan_id);
            if ($jabatan) {
                return $this->checkCache['isKurikulum'] = ($jabatan->kode_jabatan === 'wakil_kurikulum' || 
                       str_contains($jabatan->nama_jabatan, 'Kurikulum'));
            }
        }
        
        return $this->checkCache['isKurikulum'] = false;
    }

    public function isKurikulumForUnit($unitId)
    {
        if ($this->role === 'administrator' || $this->role === 'direktur') {
            return true;
        }

        // Check 1: Explicit assignment in Pivot for this Unit
        $explicit = $this->jabatanUnits()
            ->where('unit_id', $unitId)
            ->whereHas('jabatan', function($q) {
                $q->where('kode_jabatan', 'wakil_kurikulum')
                  ->orWhere('nama_jabatan', 'Wakil Kurikulum')
                  ->orWhere('nama_jabatan', 'LIKE', '%Kurikulum%');
            })->exists();
            
        if ($explicit) return true;
        
        // Check 2: User is Kurikulum (anywhere) AND accessing their Home Unit
        if ($this->unit_id == $unitId && $this->isKurikulum()) {
            return true;
        }
        
        return false;
    }

    public function isLearningManagerForUnit($unitId)
    {
        if ($this->role === 'administrator' || $this->role === 'direktur') {
            return true;
        }

        // Check 1: Explicit assignment in Pivot for this Unit (Kurikulum OR Kepala Sekolah)
        $explicit = $this->jabatanUnits()
            ->where('unit_id', $unitId)
            ->whereHas('jabatan', function($q) {
                $q->whereIn('kode_jabatan', ['wakil_kurikulum', 'kepala_sekolah'])
                  ->orWhere('nama_jabatan', 'LIKE', '%Kurikulum%')
                  ->orWhere('nama_jabatan', 'LIKE', '%Kepala Sekolah%');
            })->exists();
            
        if ($explicit) return true;
        
        // Check 2: User is Manager (anywhere) AND accessing their Home Unit (Legacy Fallback)
        // If user has role/jabatan of KS/Kurikulum and is accessing their home unit
        if ($this->unit_id == $unitId) {
             if ($this->isKurikulum() || $this->isKepalaSekolah()) {
                 return true;
             }
        }
        
        return false;
    }
    public function isWaliKelas()
    {
        if (isset($this->checkCache['isWaliKelas'])) return $this->checkCache['isWaliKelas'];

        if ($this->role === 'administrator' || $this->role === 'direktur') {
            return $this->checkCache['isWaliKelas'] = true;
        }

        // Check 1: Assigned to a class in 'classes' table
        if ($this->waliKelasOf()->exists()) {
            return $this->checkCache['isWaliKelas'] = true;
        }

        // Check 2: Has Jabatan "Wali Kelas" via Pivot (hasJabatan checks jabatanUnits)
        if ($this->hasJabatan('Wali Kelas')) {
            return $this->checkCache['isWaliKelas'] = true;
        }

        // Check 3: Check Legacy/Direct Jabatan relation or Collection
        foreach($this->jabatans as $jabatan) {
            if (stripos($jabatan->nama_jabatan, 'Wali Kelas') !== false) {
                return $this->checkCache['isWaliKelas'] = true;
            }
        }

        // Check 4: Check Legacy Jabatan ID
        if ($this->jabatan_id) {
            $legacyJabatan = \App\Models\Jabatan::find($this->jabatan_id);
            if ($legacyJabatan && stripos($legacyJabatan->nama_jabatan, 'Wali Kelas') !== false) {
                return $this->checkCache['isWaliKelas'] = true;
            }
        }

        return $this->checkCache['isWaliKelas'] = false;
    }

    /**
     * Get units where the user teaches in the active academic year
     */
    public function getTeachingUnits()
    {
        if (isset($this->checkCache['teachingUnits'])) return $this->checkCache['teachingUnits'];

        $activeAY = \App\Models\AcademicYear::where('status', 'active')->first();
        if (!$activeAY) return $this->checkCache['teachingUnits'] = collect();

        $unitIds = \App\Models\TeachingAssignment::where('teaching_assignments.user_id', $this->id)
            ->where('teaching_assignments.academic_year_id', $activeAY->id)
            ->join('classes', 'teaching_assignments.class_id', '=', 'classes.id')
            ->pluck('classes.unit_id');

        // Also include home unit if they have one
        if ($this->unit_id) {
            $unitIds->push($this->unit_id);
        }

        return $this->checkCache['teachingUnits'] = \App\Models\Unit::whereIn('id', $unitIds->unique())->get();
    }
}
