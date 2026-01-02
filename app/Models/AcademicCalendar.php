<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    //
    protected $fillable = ['unit_id', 'date', 'description', 'is_holiday'];

    protected $casts = [
        'date' => 'date',
        'is_holiday' => 'boolean',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
