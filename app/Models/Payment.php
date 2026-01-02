<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'unit_id',
        'category',
        'amount',
        'transaction_date',
        'recipient',
        'payment_method',
        'description',
        'reference_number',
        'user_id',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
