<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'academic_year_id',
        'name',
        'coach_name',
        'description',
        'status',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function members()
    {
        return $this->hasMany(ExtracurricularMember::class);
    }
}
