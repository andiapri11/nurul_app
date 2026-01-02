<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherDocumentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'user_id',
        'file_path',
        'original_filename',
        'submitted_at',
        'status',
        'feedback',
        'validated_by',
        'validated_at',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'validated_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(TeacherDocumentRequest::class, 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
