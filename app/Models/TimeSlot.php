<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = ['unit_id', 'name', 'start_time', 'end_time', 'is_break'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
