<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

final class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasUuids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'role',
        'name',
        'email',
        'facility_id',
        'branch_id',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'role' => UserRole::class,
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the route key name for Laravel's model binding.
     */
    final public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Get the columns that s
     * hould receive a unique identifier.
     *
     * @return array<int, string>
     */
    final public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * The facility the user belongs to.
     *
     * @return BelongsTo<Facility, User>
     */
    public function facility(): BelongsTo
    {
        /** @var BelongsTo<Facility, User> */
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    /**
     * The branch the user belongs to.
     *
     * @return BelongsTo<Branch, User>
     */
    public function branch(): BelongsTo
    {
        /** @var BelongsTo<Branch, User> */
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }
}
