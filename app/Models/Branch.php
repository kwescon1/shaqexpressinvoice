<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BranchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Branch extends Base
{
    /** @use HasFactory<BranchFactory> */
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
     * The facility this branch belongs to.
     *
     * @return BelongsTo<Facility, Branch>
     */
    public function facility(): BelongsTo
    {
        /** @var BelongsTo<Facility, Branch> */
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    /**
     * The users associated with this branch.
     *
     * @return HasMany<User, Branch>
     */
    public function users(): HasMany
    {
        /** @var HasMany<User, Branch> */
        return $this->hasMany(User::class, 'branch_id');
    }

    /**
     * Get the invoices associated with this branch.
     *
     * @return HasMany<Invoice, Branch>
     */
    public function invoices(): HasMany
    {
        /** @var HasMany<Invoice, Branch> */
        return $this->hasMany(Invoice::class, 'branch_id');
    }

    /**
     * The products associated with this branch.
     *
     * @return HasMany<Product, Branch>
     */
    public function products(): HasMany
    {
        /** @var HasMany<Product, Branch> */
        return $this->hasMany(Product::class, 'branch_id');
    }
}
