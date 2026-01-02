<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    protected $fillable = [
        'income_expense_id',
        'item_name',
        'quantity',
        'unit_name',
        'price',
        'total_price'
    ];

    public function expense()
    {
        return $this->belongsTo(IncomeExpense::class, 'income_expense_id');
    }
}
