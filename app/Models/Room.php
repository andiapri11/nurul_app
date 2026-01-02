<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['unit_id', 'academic_year_id', 'name', 'type', 'person_in_charge', 'description'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
