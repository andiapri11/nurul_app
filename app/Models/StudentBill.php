<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'payment_type_id',
        'academic_year_id',
        'month',
        'year',
        'amount',
        'paid_amount',
        'status',
        'due_date',
        'notes',
        'discount_amount',
        'is_free'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // A bill can have multiple transactions (if partial payment)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
