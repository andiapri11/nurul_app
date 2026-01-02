<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_request_id',
        'student_bill_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function paymentRequest()
    {
        return $this->belongsTo(PaymentRequest::class);
    }

    public function studentBill()
    {
        return $this->belongsTo(StudentBill::class);
    }
}
