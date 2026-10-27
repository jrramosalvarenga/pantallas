<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'business_name', 'contact_name', 'email', 'phone',
        'rtn', 'address', 'municipality_id', 'status', 'notes',
    ];

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function getActiveContractsCountAttribute(): int
    {
        return $this->contracts()->where('status', 'activo')->count();
    }

    public function getPendingBalanceAttribute(): float
    {
        return $this->payments()
            ->whereIn('status', ['pendiente', 'vencido'])
            ->sum('amount');
    }
}
