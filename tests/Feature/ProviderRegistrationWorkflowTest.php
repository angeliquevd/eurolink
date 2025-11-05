<?php

use App\Models\ProviderRegistration;
use App\Models\Space;
use App\Models\User;
use Livewire\Livewire;

test('authenticated users can access provider registration form for enabled spaces', function () {
    $user = User::factory()->create();
    $space = Space::factory()->create(['enable_provider_registration' => true]);

    $response = $this->actingAs($user)->get(route('provider-registrations.create', $space));

    $response->assertSuccessful();
});

test('provider registration form is not accessible for spaces without registration enabled', function () {
    $user = User::factory()->create();
    $space = Space::factory()->create(['enable_provider_registration' => false]);

    $response = $this->actingAs($user)->get(route('provider-registrations.create', $space));

    $response->assertForbidden();
});

test('guest users cannot access provider registration form', function () {
    $space = Space::factory()->create(['enable_provider_registration' => true]);

    $response = $this->get(route('provider-registrations.create', $space));

    $response->assertRedirect(route('login'));
});

test('user can submit a provider registration', function () {
    $user = User::factory()->create();
    $space = Space::factory()->create(['enable_provider_registration' => true]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\ProviderRegistrations\Create::class, ['space' => $space])
        ->set('company_name', 'Test AI Company')
        ->set('company_registration_number', 'EU123456789')
        ->set('company_country', 'Belgium')
        ->set('company_address', '123 AI Street, Brussels, 1000')
        ->set('company_website', 'https://testai.com')
        ->set('contact_person_name', $user->name)
        ->set('contact_person_title', 'CEO')
        ->set('contact_person_email', $user->email)
        ->set('contact_person_phone', '+32 2 123 4567')
        ->set('ai_systems_description', 'We develop cutting-edge AI systems')
        ->set('ai_system_types', ['general_purpose', 'high_risk'])
        ->set('intended_use_cases', 'Healthcare diagnostics and financial analysis')
        ->set('additional_notes', 'Looking forward to compliance')
        ->call('submit')
        ->assertRedirect(route('spaces.show', $space));

    $this->assertDatabaseHas('provider_registrations', [
        'space_id' => $space->id,
        'submitted_by' => $user->id,
        'company_name' => 'Test AI Company',
        'status' => 'pending',
    ]);
});

test('provider registration requires required fields', function () {
    $user = User::factory()->create();
    $space = Space::factory()->create(['enable_provider_registration' => true]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\ProviderRegistrations\Create::class, ['space' => $space])
        ->set('company_name', '')
        ->set('company_country', '')
        ->set('company_address', '')
        ->set('contact_person_name', '')
        ->set('contact_person_title', '')
        ->set('contact_person_email', '')
        ->set('ai_systems_description', '')
        ->call('submit')
        ->assertHasErrors([
            'company_name',
            'company_country',
            'company_address',
            'contact_person_name',
            'contact_person_title',
            'contact_person_email',
            'ai_systems_description',
        ]);
});

test('provider registration validates email format', function () {
    $user = User::factory()->create();
    $space = Space::factory()->create(['enable_provider_registration' => true]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\ProviderRegistrations\Create::class, ['space' => $space])
        ->set('company_name', 'Test Company')
        ->set('company_country', 'Belgium')
        ->set('company_address', '123 Street')
        ->set('contact_person_name', 'John Doe')
        ->set('contact_person_title', 'CEO')
        ->set('contact_person_email', 'invalid-email')
        ->set('ai_systems_description', 'AI systems')
        ->call('submit')
        ->assertHasErrors(['contact_person_email']);
});

test('provider registration validates url format for company website', function () {
    $user = User::factory()->create();
    $space = Space::factory()->create(['enable_provider_registration' => true]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\ProviderRegistrations\Create::class, ['space' => $space])
        ->set('company_name', 'Test Company')
        ->set('company_country', 'Belgium')
        ->set('company_address', '123 Street')
        ->set('company_website', 'not-a-url')
        ->set('contact_person_name', 'John Doe')
        ->set('contact_person_title', 'CEO')
        ->set('contact_person_email', 'john@example.com')
        ->set('ai_systems_description', 'AI systems')
        ->call('submit')
        ->assertHasErrors(['company_website']);
});

test('ec staff can view provider registrations index', function () {
    $ecStaff = User::factory()->ecStaff()->create();

    $response = $this->actingAs($ecStaff)->get(route('provider-registrations.index'));

    $response->assertSuccessful();
});

test('non ec staff cannot view provider registrations index', function () {
    $user = User::factory()->provider()->create();

    $response = $this->actingAs($user)->get(route('provider-registrations.index'));

    $response->assertForbidden();
});

test('ec staff can approve a pending registration', function () {
    $ecStaff = User::factory()->ecStaff()->create();
    $registration = ProviderRegistration::factory()->create(['status' => 'pending']);

    $this->actingAs($ecStaff);

    Livewire::test(\App\Livewire\ProviderRegistrations\Index::class)
        ->call('approve', $registration, 'Approved after review')
        ->assertDispatched('$refresh');

    expect($registration->fresh())
        ->status->toBe('approved')
        ->reviewed_by->toBe($ecStaff->id)
        ->review_notes->toBe('Approved after review');
});

test('ec staff can reject a pending registration', function () {
    $ecStaff = User::factory()->ecStaff()->create();
    $registration = ProviderRegistration::factory()->create(['status' => 'pending']);

    $this->actingAs($ecStaff);

    Livewire::test(\App\Livewire\ProviderRegistrations\Index::class)
        ->call('reject', $registration, 'Missing documentation')
        ->assertDispatched('$refresh');

    expect($registration->fresh())
        ->status->toBe('rejected')
        ->reviewed_by->toBe($ecStaff->id)
        ->review_notes->toBe('Missing documentation');
});

test('policy prevents non ec staff from approving registrations', function () {
    $user = User::factory()->provider()->create();
    $registration = ProviderRegistration::factory()->create(['status' => 'pending']);

    $this->actingAs($user);

    expect($user->can('update', $registration))->toBeFalse();
    expect($registration->fresh()->status)->toBe('pending');
});

test('policy prevents non ec staff from rejecting registrations', function () {
    $user = User::factory()->provider()->create();
    $registration = ProviderRegistration::factory()->create(['status' => 'pending']);

    $this->actingAs($user);

    expect($user->can('update', $registration))->toBeFalse();
    expect($registration->fresh()->status)->toBe('pending');
});

test('ec staff cannot approve an already reviewed registration', function () {
    $ecStaff = User::factory()->ecStaff()->create();
    $registration = ProviderRegistration::factory()->create(['status' => 'approved']);

    $this->actingAs($ecStaff);

    Livewire::test(\App\Livewire\ProviderRegistrations\Index::class)
        ->call('approve', $registration)
        ->assertForbidden();
});

test('provider registration shows on space page when enabled', function () {
    $user = User::factory()->create();
    $space = Space::factory()->create(['enable_provider_registration' => true]);
    $space->users()->attach($user->id, ['role_in_space' => 'member']);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Spaces\Show::class, ['space' => $space])
        ->assertSee('Register Platform')
        ->assertSee('Platform Registration');
});

test('provider registration does not show on space page when disabled', function () {
    $user = User::factory()->create();
    $space = Space::factory()->create(['enable_provider_registration' => false]);
    $space->users()->attach($user->id, ['role_in_space' => 'member']);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Spaces\Show::class, ['space' => $space])
        ->assertDontSee('Register Platform')
        ->assertDontSee('Platform Registration');
});

test('registration form pre-fills contact information from authenticated user', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
    $space = Space::factory()->create(['enable_provider_registration' => true]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\ProviderRegistrations\Create::class, ['space' => $space])
        ->assertSet('contact_person_name', 'John Doe')
        ->assertSet('contact_person_email', 'john@example.com');
});

test('ec staff can filter registrations by status', function () {
    $ecStaff = User::factory()->ecStaff()->create();

    $pendingRegistration = ProviderRegistration::factory()->create([
        'status' => 'pending',
        'company_name' => 'Pending Company',
    ]);
    $approvedRegistration = ProviderRegistration::factory()->create([
        'status' => 'approved',
        'company_name' => 'Approved Company',
    ]);

    $this->actingAs($ecStaff);

    Livewire::test(\App\Livewire\ProviderRegistrations\Index::class)
        ->set('statusFilter', 'pending')
        ->assertSee('Pending Company')
        ->assertDontSee('Approved Company');

    Livewire::test(\App\Livewire\ProviderRegistrations\Index::class)
        ->set('statusFilter', 'approved')
        ->assertSee('Approved Company')
        ->assertDontSee('Pending Company');
});

test('submitter can view their own registration', function () {
    $user = User::factory()->create();
    $registration = ProviderRegistration::factory()->create(['submitted_by' => $user->id]);

    $this->actingAs($user);

    expect($user->can('view', $registration))->toBeTrue();
});

test('user cannot view another users registration', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $registration = ProviderRegistration::factory()->create(['submitted_by' => $otherUser->id]);

    $this->actingAs($user);

    expect($user->can('view', $registration))->toBeFalse();
});
