<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = ['start_year', 'end_year', 'status'];

    // Accessor to maintain compatibility with existing code using 'name'
    public function getNameAttribute($value)
    {
        if ($this->start_year && $this->end_year) {
            return "{$this->start_year}/{$this->end_year}";
        }
        return $value; // Return existing 'name' column if start_year not set
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
