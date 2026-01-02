<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $fillable = ['user_id', 'user_siswa_id', 'ip_address', 'user_agent', 'login_at'];

    protected $casts = [
        'login_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'user_siswa_id');
    }
}
