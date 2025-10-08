<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Space extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'visibility',
        'description',
        'enable_provider_registration',
    ];

    protected $casts = [
        'enable_provider_registration' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->withPivot('role_in_space')
            ->withTimestamps();
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(SpaceMembership::class);
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function providerRegistrations(): HasMany
    {
        return $this->hasMany(ProviderRegistration::class);
    }

    public function isPublic(): bool
    {
        return $this->visibility === 'public';
    }

    public function isPrivate(): bool
    {
        return $this->visibility === 'private';
    }

    public function hasProviderRegistration(): bool
    {
        return $this->enable_provider_registration;
    }
}
