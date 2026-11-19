<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'name', 'description', 'client_id', 'screen_id',
        'venue', 'venue_address', 'municipality_id',
        'start_date', 'end_date', 'type', 'status',
        'quoted_price', 'final_price', 'deposit_amount', 'deposit_paid',
        'contact_name', 'contact_phone', 'technical_notes', 'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'quoted_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'deposit_paid' => 'boolean',
    ];

    public const TYPES = [
        'boda' => 'Boda',
        'quinceanera' => 'Quinceañera',
        'cumpleanos' => 'Cumpleaños',
        'corporativo' => 'Corporativo',
        'concierto' => 'Concierto',
        'festival' => 'Festival',
        'deportivo' => 'Deportivo',
        'religioso' => 'Religioso',
        'politico' => 'Político',
        'graduacion' => 'Graduación',
        'feria' => 'Feria',
        'otro' => 'Otro',
    ];

    public const STATUSES = [
        'cotizado' => 'Cotizado',
        'confirmado' => 'Confirmado',
        'en_curso' => 'En curso',
        'completado' => 'Completado',
        'cancelado' => 'Cancelado',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->start_date->isFuture() && $this->status === 'confirmado';
    }
}
