<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipality extends Model
{
    protected $fillable = ['name', 'department', 'country'];

    public function screens(): HasMany
    {
        return $this->hasMany(Screen::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
}
