<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'class_id',
        'subject_id',
        'user_id',
        'day',
        'start_time',
        'end_time',
        'is_break',
        'break_name',
    ];

    // Relasi
    public function unit() { return $this->belongsTo(Unit::class); }
    public function schoolClass() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(User::class, 'user_id'); }

    public function getIsBreakAttribute()
    {
        return $this->subject_id === null;
    }

    public function todayCheckin()
    {
        return $this->hasOne(ClassCheckin::class)->whereDate('checkin_time', now()->toDateString());
    }
}
