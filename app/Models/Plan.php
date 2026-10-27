<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name', 'type', 'ad_type_id', 'screens_count',
        'discount_percent', 'final_price', 'duration_months',
        'description', 'active',
    ];

    protected $casts = [
        'final_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function adType(): BelongsTo
    {
        return $this->belongsTo(AdType::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
