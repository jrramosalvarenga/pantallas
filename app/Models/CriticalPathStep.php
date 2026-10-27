<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriticalPathStep extends Model
{
    protected $fillable = [
        'order', 'phase', 'title', 'description', 'target_date',
        'completed_date', 'status', 'responsible', 'notes',
    ];

    protected $casts = [
        'target_date' => 'date',
        'completed_date' => 'date',
    ];
}
