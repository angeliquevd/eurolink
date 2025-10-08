<?php

use App\Models\ProviderRegistration;
use App\Models\Space;
use App\Models\User;

test('provider registration has correct default status', function () {
    $registration = ProviderRegistration::factory()->create();

    expect($registration->status)->toBe('pending');
});

test('provider registration can check if it is pending', function () {
    $registration = ProviderRegistration::factory()->create(['status' => 'pending']);

    expect($registration->isPending())->toBeTrue();
    expect($registration->isApproved())->toBeFalse();
    expect($registration->isRejected())->toBeFalse();
});

test('provider registration can check if it is approved', function () {
    $registration = ProviderRegistration::factory()->create(['status' => 'approved']);

    expect($registration->isApproved())->toBeTrue();
    expect($registration->isPending())->toBeFalse();
    expect($registration->isRejected())->toBeFalse();
});

test('provider registration can check if it is rejected', function () {
    $registration = ProviderRegistration::factory()->create(['status' => 'rejected']);

    expect($registration->isRejected())->toBeTrue();
    expect($registration->isPending())->toBeFalse();
    expect($registration->isApproved())->toBeFalse();
});

test('provider registration can be approved', function () {
    $registration = ProviderRegistration::factory()->create(['status' => 'pending']);
    $reviewer = User::factory()->ecStaff()->create();
    $notes = 'Registration approved after review';

    $registration->approve($reviewer, $notes);

    expect($registration->fresh())
        ->status->toBe('approved')
        ->reviewed_by->toBe($reviewer->id)
        ->review_notes->toBe($notes)
        ->reviewed_at->not->toBeNull();
});

test('provider registration can be rejected', function () {
    $registration = ProviderRegistration::factory()->create(['status' => 'pending']);
    $reviewer = User::factory()->ecStaff()->create();
    $notes = 'Missing required documentation';

    $registration->reject($reviewer, $notes);

    expect($registration->fresh())
        ->status->toBe('rejected')
        ->reviewed_by->toBe($reviewer->id)
        ->review_notes->toBe($notes)
        ->reviewed_at->not->toBeNull();
});

test('provider registration approval works without notes', function () {
    $registration = ProviderRegistration::factory()->create(['status' => 'pending']);
    $reviewer = User::factory()->ecStaff()->create();

    $registration->approve($reviewer);

    expect($registration->fresh())
        ->status->toBe('approved')
        ->reviewed_by->toBe($reviewer->id)
        ->review_notes->toBeNull()
        ->reviewed_at->not->toBeNull();
});

test('provider registration rejection works without notes', function () {
    $registration = ProviderRegistration::factory()->create(['status' => 'pending']);
    $reviewer = User::factory()->ecStaff()->create();

    $registration->reject($reviewer);

    expect($registration->fresh())
        ->status->toBe('rejected')
        ->reviewed_by->toBe($reviewer->id)
        ->review_notes->toBeNull()
        ->reviewed_at->not->toBeNull();
});

test('provider registration belongs to a space', function () {
    $space = Space::factory()->create();
    $registration = ProviderRegistration::factory()->create(['space_id' => $space->id]);

    expect($registration->space)->toBeInstanceOf(Space::class);
    expect($registration->space->id)->toBe($space->id);
});

test('provider registration belongs to a submitter', function () {
    $user = User::factory()->create();
    $registration = ProviderRegistration::factory()->create(['submitted_by' => $user->id]);

    expect($registration->submitter)->toBeInstanceOf(User::class);
    expect($registration->submitter->id)->toBe($user->id);
});

test('provider registration can have a reviewer', function () {
    $reviewer = User::factory()->ecStaff()->create();
    $registration = ProviderRegistration::factory()->create([
        'status' => 'approved',
        'reviewed_by' => $reviewer->id,
    ]);

    expect($registration->reviewer)->toBeInstanceOf(User::class);
    expect($registration->reviewer->id)->toBe($reviewer->id);
});

test('provider registration ai system types is cast to array', function () {
    $types = ['general_purpose', 'high_risk'];
    $registration = ProviderRegistration::factory()->create(['ai_system_types' => $types]);

    expect($registration->ai_system_types)->toBeArray();
    expect($registration->ai_system_types)->toBe($types);
});

test('provider registration reviewed_at is cast to datetime', function () {
    $registration = ProviderRegistration::factory()->create([
        'status' => 'approved',
        'reviewed_at' => now(),
    ]);

    expect($registration->reviewed_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});
