<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpaceMembership extends Model
{
    use HasFactory;

    protected $table = 'memberships';

    protected $fillable = [
        'user_id',
        'space_id',
        'role_in_space',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    public function isOwner(): bool
    {
        return $this->role_in_space === 'owner';
    }

    public function isModerator(): bool
    {
        return $this->role_in_space === 'moderator';
    }

    public function isMember(): bool
    {
        return $this->role_in_space === 'member';
    }
}
