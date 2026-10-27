<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdType extends Model
{
    protected $fillable = ['name', 'slug', 'price_per_spot', 'description', 'active'];

    protected $casts = [
        'price_per_spot' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }
}
