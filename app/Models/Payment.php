<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'contract_id', 'client_id', 'amount', 'due_date', 'paid_date',
        'status', 'amount_paid', 'payment_method', 'reference', 'notes',
        'reminder_sent',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'reminder_sent' => 'boolean',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pendiente')
            ->where('due_date', '<', now());
    }

    public function scopeUpcoming($query, int $days = 7)
    {
        return $query->where('status', 'pendiente')
            ->whereBetween('due_date', [now(), now()->addDays($days)]);
    }
}
