<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantProfile extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'booking_id',
        'phone',
        'identity_number',
        'birth_date',
        'birth_place',
        'address',
        'occupation',
        'parent_name',
        'parent_phone',
        'lease_start',
        'lease_end',
        'deposit_amount',
        'deposit_date',
        'deposit_status',
        'deposit_notes',
        'status',
        'rejection_reason',
        'approved_at',
        'checked_out_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'lease_start' => 'date',
            'lease_end' => 'date',
            'deposit_date' => 'date',
            'deposit_amount' => 'decimal:2',
            'approved_at' => 'datetime',
            'checked_out_at' => 'datetime',
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

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}