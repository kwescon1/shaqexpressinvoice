<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Product extends Base
{
    /** @use HasFactory<ProductFactory> */
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
     * The branch the prduct belongs to.
     *
     * @return BelongsTo<Category, Product>
     */
    public function category(): BelongsTo
    {
        /** @var BelongsTo<Category, Product> */
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * The branch the prduct belongs to.
     *
     * @return BelongsTo<Branch, Product>
     */
    public function branch(): BelongsTo
    {
        /** @var BelongsTo<Branch, Product> */
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * The stock associated with this product.
     *
     * @return HasOne<Stock, Product>
     */
    public function stock(): HasOne
    {
        /** @var HasOne<Stock, Product> */
        return $this->hasOne(Stock::class, 'product_id');
    }

    /**
     * The invoices associated with this product.
     *
     * @return BelongsToMany<Invoice, Product>
     */
    public function invoices(): BelongsToMany
    {
        /** @var BelongsToMany<Invoice, Product> */
        return $this->belongsToMany(Invoice::class, 'invoice_product')
            ->withPivot('quantity', 'price') // Include extra fields
            ->withTimestamps();
    }
}
