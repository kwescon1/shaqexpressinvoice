<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

abstract class Base extends Model
{
    use HasUuids;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays and JSON serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['id'];

    /**
     * Get the route key name for Laravel's model binding.
     */
    final public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    final public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
