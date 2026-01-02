<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCategory extends Model
{
    protected $fillable = ['unit_id', 'academic_year_id', 'name', 'description', 'is_consumable'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function consumables()
    {
        return $this->hasMany(Consumable::class, 'inventory_category_id');
    }
}
