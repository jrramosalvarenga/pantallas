<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Screen extends Model
{
    protected $fillable = [
        'name', 'code', 'municipality_id', 'address', 'gps_lat', 'gps_lng',
        'total_spaces', 'spot_duration_seconds', 'cycle_duration_seconds',
        'operating_hours', 'investment_cost', 'book_value', 'useful_life_months',
        'purchase_date', 'monthly_depreciation', 'status', 'notes',
    ];

    protected $casts = [
        'investment_cost' => 'decimal:2',
        'book_value' => 'decimal:2',
        'monthly_depreciation' => 'decimal:2',
        'purchase_date' => 'date',
    ];

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class)->withPivot('space_number')->withTimestamps();
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function getOccupiedSpacesAttribute(): int
    {
        return $this->contracts()
            ->whereIn('contracts.status', ['activo'])
            ->count();
    }

    public function getAvailableSpacesAttribute(): int
    {
        return $this->total_spaces - $this->occupied_spaces;
    }

    public function getOccupancyPercentAttribute(): float
    {
        if ($this->total_spaces === 0) return 0;
        return round(($this->occupied_spaces / $this->total_spaces) * 100, 1);
    }

    public function getDailyPlaysAttribute(): int
    {
        if ($this->cycle_duration_seconds === 0) return 0;
        $hours = $this->getOperatingHoursCount();
        return (int) (($hours * 3600) / $this->cycle_duration_seconds);
    }

    private function getOperatingHoursCount(): int
    {
        $parts = explode('-', $this->operating_hours);
        if (count($parts) !== 2) return 14;
        $start = (int) explode(':', $parts[0])[0];
        $end = (int) explode(':', $parts[1])[0];
        return $end - $start;
    }
}
