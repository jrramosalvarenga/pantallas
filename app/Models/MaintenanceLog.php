<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    protected $fillable = [
        'screen_id', 'date', 'type', 'title', 'description',
        'technician', 'cost', 'next_maintenance_date', 'status', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'next_maintenance_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }
}
