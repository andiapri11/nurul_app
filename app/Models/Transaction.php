<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function user() // Receiver
    {
        return $this->belongsTo(User::class);
    }
    
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    // Auto-generate Invoice Number
    protected static function booted()
    {
        static::creating(function ($transaction) {
            if (!$transaction->invoice_number) {
                $transaction->invoice_number = 'INV-'.date('YmdHis').'-'.rand(100,999);
            }
        });
    }
}
