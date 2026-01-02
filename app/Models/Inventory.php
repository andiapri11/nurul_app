<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'inventory_category_id', 
        'room_id', 
        'name', 
        'code', 
        'condition', 
        'price', 
        'source',
        'person_in_charge',
        'is_grant',
        'purchase_date',
        'photo',
        'disposal_reason',
        'disposal_photo'
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }

    public function logs()
    {
        return $this->hasMany(InventoryLog::class)->latest();
    }

    public function getFullHistoryAttribute()
    {
        $logs = $this->logs->map(function($log) {
            return [
                'date' => $log->created_at,
                'action' => $log->action,
                'details' => $log->details,
                'user' => $log->user->name ?? 'System',
                'type' => 'log'
            ];
        });

        $reports = $this->damageReports->map(function($report) {
            return [
                'date' => $report->created_at,
                'action' => 'Damage/Loss Reported',
                'details' => "[{$report->type}] {$report->description}. Action: {$report->follow_up_action}",
                'user' => $report->user->name ?? 'System',
                'type' => 'report',
                'status' => $report->status
            ];
        });

        return $logs->concat($reports)->sortByDesc('date')->values();
    }
}
