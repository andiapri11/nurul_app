<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    //
    protected $fillable = ['unit_id', 'date', 'description', 'is_holiday', 'affected_classes'];

    protected $casts = [
        'date' => 'date',
        'is_holiday' => 'boolean',
        'affected_classes' => 'array',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
