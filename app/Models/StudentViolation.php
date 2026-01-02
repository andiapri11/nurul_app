<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentViolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'violation_type',
        'description',
        'points',
        'proof',
        'follow_up',
        'follow_up_result',
        'follow_up_attachment',
        'follow_up_status',
        'recorded_by',
        'academic_year_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
