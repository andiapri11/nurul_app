<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GraduationAnnouncement extends Model
{
    protected $fillable = [
        'academic_year_id',
        'unit_id',
        'is_active',
        'title',
        'description',
        'announcement_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'announcement_date' => 'datetime',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function results()
    {
        return $this->hasMany(StudentGraduationResult::class);
    }
}
