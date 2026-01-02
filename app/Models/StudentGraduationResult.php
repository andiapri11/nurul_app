<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGraduationResult extends Model
{
    protected $fillable = [
        'graduation_announcement_id',
        'student_id',
        'status',
        'message',
        'skl_file'
    ];

    public function announcement()
    {
        return $this->belongsTo(GraduationAnnouncement::class, 'graduation_announcement_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
