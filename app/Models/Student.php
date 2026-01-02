<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_siswa_id',
        'unit_id',
        'nis',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'is_boarding',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'alamat_rt',
        'alamat_rw',
        'desa',
        'kecamatan',
        'kota',
        'kode_pos',
        'no_hp',
        'nama_wali',
        'no_hp_wali',
        'status',
        'withdrawal_proof',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_boarding' => 'boolean',
    ];

    public function setTanggalLahirAttribute($value)
    {
        if ($value) {
            try {
                $this->attributes['tanggal_lahir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                     $this->attributes['tanggal_lahir'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
                } catch (\Exception $ex) {
                    $this->attributes['tanggal_lahir'] = $value;
                }
            }
        } else {
            $this->attributes['tanggal_lahir'] = null;
        }
    }

    public function user()
    {
        return $this->belongsTo(UserSiswa::class, 'user_siswa_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Relationship to retrieve the student's class for the active academic year.
     * Maintained for backward compatibility.
     */
    public function schoolClass()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student', 'student_id', 'class_id')
                    ->whereHas('academicYear', function($q) {
                        $q->where('status', 'active');
                    })
                    ->withPivot('academic_year_id');
    }

    /**
     * The classes that belong to the student (History).
     */
    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student', 'student_id', 'class_id')
                    ->withPivot('academic_year_id')
                    ->withTimestamps();
    }

    /**
     * Get the latest class history record.
     */
    public function latestClassHistory()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student', 'student_id', 'class_id')
                    ->withPivot('academic_year_id')
                    ->latest('class_student.created_at');
    }

    /**
     * Get the student's class for a specific academic year.
     */
    public function classInYear($yearId)
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student', 'student_id', 'class_id')
                    ->where('class_student.academic_year_id', $yearId);
    }

    /**
     * Get the student's class for the active academic year.
     */
    public function activeClass()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student', 'student_id', 'class_id')
                    ->whereHas('academicYear', function($q) {
                        $q->where('status', 'active');
                    });
    }

    public function attendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function violationRecords()
    {
        return $this->hasMany(StudentViolation::class);
    }

    public function violations()
    {
        return $this->hasMany(StudentViolation::class);
    }

    public function achievements()
    {
        return $this->hasMany(StudentAchievement::class);
    }

    public function extracurriculars()
    {
        return $this->hasMany(ExtracurricularMember::class);
    }

    /**
     * Get the student's bills.
     */
    public function bills()
    {
        return $this->hasMany(StudentBill::class);
    }
}
