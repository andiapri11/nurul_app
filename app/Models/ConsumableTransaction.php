<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumableTransaction extends Model
{
    protected $fillable = ['consumable_id', 'user_id', 'quantity', 'type', 'note'];

    public function consumable()
    {
        return $this->belongsTo(Consumable::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
