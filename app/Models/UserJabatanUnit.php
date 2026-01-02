<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserJabatanUnit extends Pivot
{
    use HasFactory;
    
    protected $table = 'user_jabatan_units';

    protected $fillable = [
        'user_id',
        'jabatan_id',
        'unit_id',
        'academic_year_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
