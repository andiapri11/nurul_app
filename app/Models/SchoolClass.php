<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['name', 'unit_id', 'grade_code', 'code', 'teacher_id', 'student_leader_id', 'academic_year_id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function leader()
    {
        return $this->belongsTo(Student::class, 'student_leader_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_student', 'class_id', 'student_id')
                    ->withPivot('academic_year_id')
                    ->withTimestamps();
    }

    public function studentHistory()
    {
        return $this->belongsToMany(Student::class, 'class_student', 'class_id', 'student_id')
                    ->withPivot('academic_year_id')
                    ->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    public function teachingAssignments()
    {
        return $this->hasMany(TeachingAssignment::class, 'class_id');
    }

    public function scopeForActiveAcademicYear($query)
    {
        return $query->whereHas('academicYear', function ($q) {
            $q->where('status', 'active');
        });
    }
}
