<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectInteraction extends Model
{
    protected $fillable = [
        'prospect_id', 'user_id', 'type', 'notes',
        'result', 'interaction_date', 'next_followup_date',
    ];

    protected $casts = [
        'interaction_date' => 'datetime',
        'next_followup_date' => 'date',
    ];

    public const TYPES = [
        'llamada' => 'Llamada',
        'visita' => 'Visita',
        'whatsapp' => 'WhatsApp',
        'email' => 'Email',
        'otro' => 'Otro',
    ];

    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
