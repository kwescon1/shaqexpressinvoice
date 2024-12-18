<?php

declare(strict_types=1);

namespace App\Providers;

use App\Builders\InvoiceQueryBuilder;
use App\Contracts\Builders\QueryInvoice;
use App\Contracts\Services\AuthServiceInterface;
use App\Contracts\Services\CrudInterface;
use App\Contracts\Services\DetectBotInterface;
use App\Contracts\Services\GeneratesInvoiceNumber;
use App\Contracts\Services\ManagesItem;
use App\Contracts\Services\ProcessInvoice;
use App\Contracts\Services\ProvidesLatestInvoice;
use App\Contracts\Services\Searchable;
use App\Contracts\Services\Sellable;
use App\Contracts\Services\UpdateInvoiceItemQuantity;
use App\Http\Controllers\InvoiceController;
use App\Services\Auth\AuthService;
use App\Services\Firewall;
use App\Services\Invoice\InvoiceNumberService;
use App\Services\Invoice\InvoiceService;
use App\Services\Product\ProductService;
use App\Services\Product\SoldProductService;
use Illuminate\Support\ServiceProvider;

final class BindingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DetectBotInterface::class, Firewall::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(Searchable::class, ProductService::class);
        $this->app->bind(ManagesItem::class, InvoiceService::class);
        $this->app->bind(ProvidesLatestInvoice::class, InvoiceNumberService::class);
        $this->app->bind(UpdateInvoiceItemQuantity::class, InvoiceService::class);
        $this->app->bind(ProcessInvoice::class, InvoiceService::class);
        $this->app->bind(QueryInvoice::class, InvoiceQueryBuilder::class);
        $this->app->bind(GeneratesInvoiceNumber::class, InvoiceNumberService::class);
        $this->app->bind(Sellable::class, SoldProductService::class);

        $this->app->when(InvoiceController::class)
            ->needs(CrudInterface::class)
            ->give(InvoiceService::class);
    }
}
