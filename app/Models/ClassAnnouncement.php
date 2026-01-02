<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'user_id',
        'title',
        'content',
        'attachment',
        'original_filename',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
