<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserSiswa extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_siswa';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'plain_password',
        'photo', // Assuming students also have photos
        'login_attempts',
        'locked_at',
        'status', // 'aktif' or 'non-aktif'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function student()
    {
        return $this->hasOne(Student::class, 'user_siswa_id');
    }

    public function getRoleAttribute()
    {
        return 'siswa';
    }
}
