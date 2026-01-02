<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'code',
        'name',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
