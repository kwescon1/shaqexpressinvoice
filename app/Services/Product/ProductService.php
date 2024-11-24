<?php

declare(strict_types=1);

namespace App\Services\Product;

use App\Contracts\Services\Searchable;
use App\Models\Product;
use Illuminate\Support\Collection;

final class ProductService implements Searchable
{
    /**
     * ProductService constructor.
     *
     * @param  Product  $product  The Product model instance.
     */
    public function __construct(private Product $product)
    {
        // Constructor injection for the Product model
    }

    /**
     * Search for products based on name and branch ID.
     *
     * @param  int  $branchId  The branch ID to filter products.
     * @param  string  $query  The search query string (product name).
     * @return Collection<int, Product> The search results as a collection.
     */
    public function search(int $branchId, string $query): Collection
    {
        return $this->product
            ->newQuery()
            ->where('branch_id', $branchId) // Filter by branch ID
            ->where('name', 'like', '%'.$query.'%') // Search by name
            ->get(); // Return as a collection
    }
}
