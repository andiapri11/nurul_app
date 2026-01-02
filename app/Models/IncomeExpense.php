<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'type',
        'category',
        'amount',
        'payment_method',
        'bank_account_id',
        'payer_name',
        'transaction_date',
        'description',
        'nota',
        'photo',
        'procurement_request_code',
        'user_id',
        'is_proof_needed',
        'proof_status',
        'proof_code'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ExpenseItem::class);
    }
}
