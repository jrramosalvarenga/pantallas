<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    protected $fillable = [
        'contract_number', 'client_id', 'plan_id', 'start_date', 'end_date',
        'monthly_amount', 'total_amount', 'payment_frequency', 'status', 'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function screens(): BelongsToMany
    {
        return $this->belongsToMany(Screen::class)->withPivot('space_number')->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'activo' && $this->end_date->isFuture();
    }

    public static function generateContractNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->count();
        return sprintf('KP-%s-%04d', $year, $last + 1);
    }
}
