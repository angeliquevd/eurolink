<?php

namespace App\Livewire\ProviderRegistrations;

use App\Models\ProviderRegistration;
use App\Models\Space;
use Livewire\Component;

class Create extends Component
{
    public Space $space;

    // Company Information
    public string $company_name = '';
    public string $company_registration_number = '';
    public string $company_country = '';
    public string $company_address = '';
    public string $company_website = '';

    // Contact Information
    public string $contact_person_name = '';
    public string $contact_person_title = '';
    public string $contact_person_email = '';
    public string $contact_person_phone = '';

    // AI Information
    public string $ai_systems_description = '';
    public array $ai_system_types = [];
    public string $intended_use_cases = '';

    // Additional
    public string $additional_notes = '';

    public function mount(Space $space): void
    {
        $this->authorize('create', ProviderRegistration::class);

        if (! $space->hasProviderRegistration()) {
            abort(403, 'Provider registration is not enabled for this space.');
        }

        $this->space = $space;

        // Pre-fill contact information from authenticated user
        $user = auth()->user();
        $this->contact_person_name = $user->name;
        $this->contact_person_email = $user->email;
    }

    public function submit()
    {
        $validated = $this->validate([
            'company_name' => 'required|string|max:255',
            'company_registration_number' => 'nullable|string|max:255',
            'company_country' => 'required|string|max:255',
            'company_address' => 'required|string',
            'company_website' => 'nullable|url|max:255',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_title' => 'required|string|max:255',
            'contact_person_email' => 'required|email|max:255',
            'contact_person_phone' => 'nullable|string|max:255',
            'ai_systems_description' => 'required|string',
            'ai_system_types' => 'nullable|array',
            'intended_use_cases' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ]);

        $registration = ProviderRegistration::create([
            ...$validated,
            'space_id' => $this->space->id,
            'submitted_by' => auth()->id(),
            'status' => 'pending',
        ]);

        session()->flash('message', 'Your provider registration has been submitted successfully. You will be notified once it has been reviewed.');

        return $this->redirect(route('spaces.show', $this->space), navigate: true);
    }

    public function render()
    {
        $userSpaces = auth()->user()->spaces()->orderBy('name')->get();

        return view('livewire.provider-registrations.create')
            ->layout('layouts.sidebar', ['spaces' => $userSpaces]);
    }
}
