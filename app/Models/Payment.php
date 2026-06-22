<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'period_label',
        'amount',
        'due_date',
        'paid_date',
        'status',
        'payment_method',
        'midtrans_order_id',
        'midtrans_snap_token',
        'midtrans_response',
        'midtrans_transaction_status',
        'midtrans_transaction_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}