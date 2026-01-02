<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'black_book_points'];

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'unit_id');
    }

    public function jabatans()
    {
        return $this->hasMany(Jabatan::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function categories()
    {
        return $this->hasMany(InventoryCategory::class);
    }

    public function getSarprasOfficerName()
    {
        $officer = \App\Models\User::whereHas('jabatanUnits', function($q) {
            $q->where('unit_id', $this->id)
              ->whereHas('jabatan', function($sq) {
                  $sq->where('kode_jabatan', 'wakil_sarana_prasarana')
                    ->orWhere('nama_jabatan', 'LIKE', '%Sarana%')
                    ->orWhere('nama_jabatan', 'LIKE', '%Sarpras%');
              });
        })->first();

        return $officer ? $officer->name : null;
    }
}
