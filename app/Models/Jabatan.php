<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_jabatan',
        'nama_jabatan',
        'kategori',
        'tipe',
        'unit_id',
    ];

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            UserJabatanUnit::class,
            'jabatan_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
