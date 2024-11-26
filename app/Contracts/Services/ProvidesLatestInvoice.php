<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Invoice;
use App\Models\User;

interface ProvidesLatestInvoice
{
    public function latestCreatedInvoice(User $user): ?Invoice;
}
