<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervision extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'academic_year_id',
        'supervisor_id',
        'teacher_id',
        'date',
        'time',
        'status',
        'notes',
        'teacher_document_path',
        'supervisor_document_path',
        'document_status',
        'subject_id',
        'school_class_id',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime', // Will cast to Carbon instance, but format might need attention
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
}
