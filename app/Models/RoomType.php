<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['unit_id', 'academic_year_id', 'name', 'label', 'color'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'type', 'name');
    }
}
