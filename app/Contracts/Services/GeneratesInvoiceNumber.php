<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Branch;
use App\Models\Facility;

interface GeneratesInvoiceNumber
{
    public function generateInvoiceNumber(Facility $facility, Branch $branch): string;
}
