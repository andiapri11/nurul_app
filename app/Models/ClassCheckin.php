<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassCheckin extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($checkin) {
            if ($checkin->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($checkin->photo);
            }
        });
    }

    protected $fillable = [
        'schedule_id',
        'user_id',
        'checkin_time',
        'checkout_time',
        'status',
        'notes',
        'photo',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'checkin_time' => 'datetime',
        'checkout_time' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
