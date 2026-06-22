<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'number',
        'type',
        'price',
        'capacity',
        'slots',
        'description',
        'facilities',
        'photos',
        'floor',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'floor' => 'integer',
            'capacity' => 'integer',
            'slots' => 'integer',
        ];
    }

    public function tenantProfiles(): HasMany
    {
        return $this->hasMany(TenantProfile::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Nama kamar untuk display (number + type).
     */
    public function getNameAttribute(): string
    {
        $typeLabels = ['standard' => 'Standard', 'deluxe' => 'Deluxe', 'premium' => 'Premium'];

        return 'Kamar ' . $this->number . ' - ' . ($typeLabels[$this->type] ?? $this->type);
    }

    protected $appends = ['name', 'status_label', 'status_color'];

    /**
     * Cek ketersediaan slot kamar.
     */
    public function hasAvailableSlots(): bool
    {
        return $this->slots > 0;
    }

    /**
     * Kurangi slot kamar sebanyak 1.
     */
    public function decreaseSlot(): void
    {
        if ($this->slots > 0) {
            $this->decrement('slots');
            $this->refresh();

            if ($this->slots === 0) {
                $this->update(['status' => 'occupied']);
            }
        }
    }

    /**
     * Tambah slot kamar sebanyak 1 (jika booking dibatalkan/gagal).
     */
    public function increaseSlot(): void
    {
        $this->increment('slots');
        $this->refresh();

        if ($this->status === 'occupied' && $this->slots > 0) {
            $this->update(['status' => 'available']);
        }
    }

    /**
     * Get status display untuk user.
     */
    public function getStatusLabelAttribute(): string
    {
        $totalSlots = max($this->capacity, 1);
        $availableSlots = $this->slots;

        if ($availableSlots <= 0) {
            return 'Penuh';
        }

        if ($availableSlots <= ($totalSlots * 0.3)) {
            return 'Hampir Penuh';
        }

        return 'Tersedia';
    }

    /**
     * Get status color untuk badge.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status_label) {
            'Penuh' => '#EF4444',
            'Hampir Penuh' => '#F59E0B',
            'Tersedia' => '#10B981',
            default => '#10B981',
        };
    }
}
