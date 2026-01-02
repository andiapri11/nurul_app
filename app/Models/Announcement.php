<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'unit_id',
        'title',
        'content',
        'type',
        'image',
        'is_active',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
