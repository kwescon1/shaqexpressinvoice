<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use Illuminate\Http\Request;

interface DetectBotInterface
{
    /**
     * Determine if a request is from a bot
     */
    public function isBot(Request $request): bool;
}
