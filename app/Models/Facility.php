<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FacilityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Facility extends Base
{
    /** @use HasFactory<FacilityFactory> */
    use HasFactory,SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the users associated with this facility.
     *
     * @return HasMany<User, Facility>
     */
    public function users(): HasMany
    {
        /** @var HasMany<User, Facility> */
        return $this->hasMany(User::class, 'facility_id');
    }

    /**
     * Get the branches associated with this facility.
     *
     * @return HasMany<Branch, Facility>
     */
    public function branches(): HasMany
    {
        /** @var HasMany<Branch, Facility> */
        return $this->hasMany(Branch::class, 'facility_id');
    }
}
