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
        'status', 'plan_of_interest', 'deal_amount', 'payment_frequency',
        'payment_start_date', 'payment_end_date', 'visit_date', 'next_followup_date',
        'notes', 'logo_path', 'ad_video_path', 'ad_video_original_name',
        'converted_to_client_id', 'converted_at',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'next_followup_date' => 'date',
        'payment_start_date' => 'date',
        'payment_end_date' => 'date',
        'converted_at' => 'datetime',
        'deal_amount' => 'decimal:2',
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

    public const PAYMENT_FREQUENCIES = [
        'mensual' => 'Mensual',
        'quincenal' => 'Quincenal',
        'semanal' => 'Semanal',
        'unico' => 'Pago único',
    ];

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function convertedClient(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'converted_to_client_id');
    }

    public function isConverted(): bool
    {
        return $this->converted_to_client_id !== null;
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
