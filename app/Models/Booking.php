<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_message',
        'room_name',
        'room_price',
        'status',
        'payment_method',
        'midtrans_order_id',
        'midtrans_snap_token',
        'midtrans_response',
        'midtrans_transaction_status',
        'midtrans_transaction_id',
        'gross_amount',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'room_price' => 'decimal:2',
            'gross_amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function tenantProfile()
    {
        return $this->hasOne(\App\Models\TenantProfile::class);
    }
}