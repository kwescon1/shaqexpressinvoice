<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Category extends Base
{
    /** @use HasFactory<CategoryFactory> */
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
     * The products associated with this category.
     *
     * @return HasMany<Product, Category>
     */
    public function products(): HasMany
    {
        /** @var HasMany<Product, Category> */
        return $this->hasMany(Product::class, 'category_id');
    }
}
