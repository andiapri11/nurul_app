<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'payment_type_id',
        'academic_year_id',
        'month',
        'due_month',
        'amount',
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
}
