<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'bank_name', 'principal_amount', 'interest_rate', 'term_months',
        'grace_months', 'monthly_payment', 'total_to_pay', 'total_interest',
        'start_date', 'status', 'notes',
    ];

    protected $casts = [
        'principal_amount' => 'decimal:2',
        'interest_rate' => 'decimal:4',
        'monthly_payment' => 'decimal:2',
        'total_to_pay' => 'decimal:2',
        'total_interest' => 'decimal:2',
        'start_date' => 'date',
    ];

    public function getRemainingMonthsAttribute(): int
    {
        if (!$this->start_date) return $this->term_months;
        $elapsed = $this->start_date->diffInMonths(now());
        return max(0, $this->term_months - $elapsed);
    }

    public function getRemainingBalanceAttribute(): float
    {
        return $this->remaining_months * $this->monthly_payment;
    }
}
