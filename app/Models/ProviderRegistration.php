<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'space_id',
        'submitted_by',
        'company_name',
        'company_registration_number',
        'company_country',
        'company_address',
        'company_website',
        'contact_person_name',
        'contact_person_title',
        'contact_person_email',
        'contact_person_phone',
        'ai_systems_description',
        'ai_system_types',
        'intended_use_cases',
        'additional_notes',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'ai_system_types' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ProviderRegistrationStatusHistory::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approve(User $reviewer, ?string $notes = null): void
    {
        $oldStatus = $this->status;

        $this->update([
            'status' => 'approved',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'review_notes' => $notes,
        ]);

        // Track status change
        $this->recordStatusChange($oldStatus, 'approved', $reviewer, $notes);

        // Send approval message to submitter
        $this->sendNotificationMessage(
            sender: $reviewer,
            recipient: $this->submitter,
            subject: 'Your AI Provider Registration has been approved',
            body: "Good news! Your registration for {$this->company_name} has been approved by the European Commission.\n\n".
                  ($notes ? "Reviewer notes: {$notes}\n\n" : '').
                  'You can now participate fully in the AI Office space. If you have any questions, please reply to this message.'
        );
    }

    public function reject(User $reviewer, ?string $notes = null): void
    {
        $oldStatus = $this->status;

        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'review_notes' => $notes,
        ]);

        // Track status change
        $this->recordStatusChange($oldStatus, 'rejected', $reviewer, $notes);

        // Send rejection message to submitter
        $this->sendNotificationMessage(
            sender: $reviewer,
            recipient: $this->submitter,
            subject: 'Your AI Provider Registration requires additional information',
            body: "Thank you for submitting your registration for {$this->company_name}. After careful review, we need additional information before we can approve your application.\n\n".
                  ($notes ? "Reason: {$notes}\n\n" : '').
                  'Please reply to this message with the requested information, and we will review your application again.'
        );
    }

    public function reopenForReview(User $provider, string $reason = 'Provider submitted additional information'): void
    {
        $oldStatus = $this->status;

        $this->update([
            'status' => 'pending',
        ]);

        // Track status change
        $this->recordStatusChange($oldStatus, 'pending', $provider, $reason);
    }

    protected function recordStatusChange(string $fromStatus, string $toStatus, User $changedBy, ?string $notes = null): void
    {
        ProviderRegistrationStatusHistory::create([
            'provider_registration_id' => $this->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => $changedBy->id,
            'notes' => $notes,
        ]);
    }

    protected function sendNotificationMessage(User $sender, User $recipient, string $subject, string $body): void
    {
        Message::create([
            'provider_registration_id' => $this->id,
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'subject' => $subject,
            'body' => $body,
        ]);
    }
}
