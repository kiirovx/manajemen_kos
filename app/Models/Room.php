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
        'floor',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'floor' => 'integer',
        ];
    }

    public function tenantProfiles(): HasMany
    {
        return $this->hasMany(TenantProfile::class);
    }
}
