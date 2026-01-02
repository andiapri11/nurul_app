<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementRequest extends Model
{
    protected $fillable = [
        'request_code',
        'activity_name',
        'activity_description',
        'unit_id',
        'academic_year_id',
        'user_id',
        'inventory_category_id',
        'item_name',
        'quantity',
        'unit_name',
        'estimated_price',
        'description',
        'type',
        'status',
        'principal_status',
        'principal_note',
        'validated_at',
        'director_status',
        'director_note',
        'approved_at',
        'approved_price',
        'approved_quantity',
        'photo',
        'report_nota',
        'report_photo',
        'report_status',
        'report_at',
        'report_note',
        'finance_approved_at',
        'finance_note'
    ];

    protected $casts = [
        'validated_at' => 'datetime',
        'approved_at' => 'datetime',
        'report_at' => 'datetime',
        'finance_approved_at' => 'datetime',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }
}
