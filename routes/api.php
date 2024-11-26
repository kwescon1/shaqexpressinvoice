<?php

declare(strict_types=1);

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Invokable\AddProductToInvoiceController;
use App\Http\Controllers\Invokable\InvoiceProcessingController;
use App\Http\Controllers\Invokable\LoginController;
use App\Http\Controllers\Invokable\ProductSearchController;
use App\Http\Controllers\Invokable\RemoveProductFromInvoiceController;
use App\Http\Controllers\Invokable\RemoveProductsFromInvoiceController;
use App\Http\Controllers\Invokable\UpdateProductQuantityOnInvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    // Auth routes
    Route::post('/login', LoginController::class)->name('api.auth.login');

    // Protected routes for authenticated users
    Route::middleware('auth:sanctum')->group(function (): void {
        // Add more routes here that require authentication
        Route::get('/search-product', ProductSearchController::class)->name('product.search');

        // create invoice
        Route::apiResource('invoice', InvoiceController::class)->only('store')->names('invoice');

        // Add item to invoice
        Route::post('/invoice/{product}/add-product', AddProductToInvoiceController::class)->name('item.add');

        // Update item quantity
        Route::put('/invoice/{product}/update-quantity', UpdateProductQuantityOnInvoiceController::class)->name('item.update');

        // Remove item from invoice
        Route::post('/invoice/{product}/remove-product', RemoveProductFromInvoiceController::class)->name('item.remove');

        // Remove all items from invoice
        Route::post('/invoice/remove-products', RemoveProductsFromInvoiceController::class)->name('item.remove-all');

        // Finalize Invoice Processing
        Route::post('/invoice/process', InvoiceProcessingController::class)->name('invoice.process');
    });
});
