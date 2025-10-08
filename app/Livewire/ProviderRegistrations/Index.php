<?php

namespace App\Livewire\ProviderRegistrations;

use App\Models\ProviderRegistration;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $statusFilter = 'all'; // all, pending, approved, rejected

    public function approve(ProviderRegistration $registration, ?string $notes = null): void
    {
        $this->authorize('update', $registration);

        $registration->approve(auth()->user(), $notes);

        session()->flash('message', 'Provider registration approved successfully.');

        $this->dispatch('$refresh');
    }

    public function reject(ProviderRegistration $registration, ?string $notes = null): void
    {
        $this->authorize('update', $registration);

        $registration->reject(auth()->user(), $notes);

        session()->flash('message', 'Provider registration rejected.');

        $this->dispatch('$refresh');
    }

    public function mount(): void
    {
        $this->authorize('viewAny', ProviderRegistration::class);
    }

    public function render()
    {
        $registrationsQuery = ProviderRegistration::query()
            ->with(['space', 'submitter', 'reviewer'])
            ->when($this->statusFilter !== 'all', fn($query) => $query->where('status', $this->statusFilter))
            ->latest();

        $registrations = $registrationsQuery->paginate(20);

        $userSpaces = auth()->user()->spaces()->orderBy('name')->get();

        return view('livewire.provider-registrations.index', compact('registrations'))
            ->layout('layouts.sidebar', ['spaces' => $userSpaces]);
    }
}
