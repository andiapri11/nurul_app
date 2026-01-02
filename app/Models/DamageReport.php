<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    protected $fillable = [
        'inventory_id',
        'user_id',
        'type',
        'description',
        'photo',
        'priority',
        'status',
        'admin_note',
        'follow_up_action',
        'follow_up_description',
        'principal_approval_status',
        'principal_id',
        'principal_note',
        'director_status',
        'director_id',
        'director_note',
    ];

    public function principal()
    {
        return $this->belongsTo(User::class, 'principal_id');
    }

    public function director()
    {
        return $this->belongsTo(User::class, 'director_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
