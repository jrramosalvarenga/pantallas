<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prospect extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name', 'category', 'municipality_id', 'phone', 'address',
        'contact_name', 'origin', 'data_source', 'nearby_screen', 'priority',
        'status', 'plan_of_interest', 'visit_date', 'next_followup_date',
        'notes', 'logo_path',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'next_followup_date' => 'date',
    ];

    public const STATUSES = [
        'por_visitar' => 'Por visitar',
        'contactado' => 'Contactado',
        'interesado' => 'Interesado',
        'negociando' => 'Negociando',
        'firmado' => 'Firmado',
        'no_interesado' => 'No interesado',
    ];

    public const PRIORITIES = [
        'A' => 'Alta',
        'B' => 'Media',
        'C' => 'Baja',
    ];

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(ProspectInteraction::class)->latest('interaction_date');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getLastInteractionAttribute()
    {
        return $this->interactions()->first();
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePendingFollowup($query)
    {
        return $query->whereNotNull('next_followup_date')
            ->where('next_followup_date', '<=', now());
    }
}
