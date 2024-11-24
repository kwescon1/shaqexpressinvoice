<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invokable;

use App\Contracts\Services\Searchable;
use App\Http\Requests\SearchProduct;
use Illuminate\Http\JsonResponse;

final class ProductSearchController
{
    public function __construct(private Searchable $product) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(SearchProduct $request): JsonResponse
    {
        // Access the sanitized 'product' parameter
        $product = (string) $request->validated()['product'];

        // Get branch ID from cache or other source
        $branchId = 1;

        // Perform the search
        $results = $this->product->search($branchId, $product);

        return response()->json($results);
    }
}
