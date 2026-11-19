<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientTask extends Model
{
    protected $fillable = [
        'client_id', 'contract_id', 'assigned_to', 'created_by',
        'title', 'description', 'type', 'priority', 'status',
        'due_date', 'completed_at', 'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public const TYPES = [
        'crear_anuncio' => 'Crear anuncio',
        'modificar_anuncio' => 'Modificar anuncio',
        'renovar_contrato' => 'Renovar contrato',
        'soporte_tecnico' => 'Soporte técnico',
        'cobranza' => 'Cobranza',
        'diseno' => 'Diseño',
        'instalacion' => 'Instalación',
        'otro' => 'Otro',
    ];

    public const PRIORITIES = [
        'baja' => 'Baja',
        'media' => 'Media',
        'alta' => 'Alta',
        'urgente' => 'Urgente',
    ];

    public const STATUSES = [
        'pendiente' => 'Pendiente',
        'en_progreso' => 'En progreso',
        'completada' => 'Completada',
        'cancelada' => 'Cancelada',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && in_array($this->status, ['pendiente', 'en_progreso']);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pendiente', 'en_progreso']);
    }

    public function scopeOverdue($query)
    {
        return $query->pending()->where('due_date', '<', now());
    }
}
