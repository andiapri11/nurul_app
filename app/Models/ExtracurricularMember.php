<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtracurricularMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'extracurricular_id',
        'student_id',
        'academic_year_id',
        'role',
        'grade_ganjil',
        'description_ganjil',
        'grade_genap',
        'description_genap',
    ];

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
