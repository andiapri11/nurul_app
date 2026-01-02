<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    protected $fillable = ['inventory_category_id', 'unit_id', 'academic_year_id', 'name', 'stock', 'unit_name', 'min_stock'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }

    public function transactions()
    {
        return $this->hasMany(ConsumableTransaction::class);
    }
}
