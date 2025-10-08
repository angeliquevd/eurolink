<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderRegistrationStatusHistory extends Model
{
    protected $fillable = [
        'provider_registration_id',
        'from_status',
        'to_status',
        'changed_by',
        'notes',
    ];

    public function providerRegistration(): BelongsTo
    {
        return $this->belongsTo(ProviderRegistration::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
