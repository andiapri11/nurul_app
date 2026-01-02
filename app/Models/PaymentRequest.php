<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'total_amount',
        'reference_code',
        'bank_account_id',
        'proof_image',
        'notes',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function items()
    {
        return $this->hasMany(PaymentRequestItem::class);
    }

    public function getTransactionAttribute()
    {
        return \App\Models\Transaction::where('notes', 'Verifikasi Pembayaran Online #' . $this->id)->first();
    }
}
