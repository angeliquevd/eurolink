<?php

namespace App\Livewire\ProviderRegistrations;

use App\Models\ProviderRegistration;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $statusFilter = 'all'; // all, pending, approved, rejected

    public ?int $approvingRegistrationId = null;

    public string $approvalNotes = '';

    public ?int $rejectingRegistrationId = null;

    public string $rejectionNotes = '';

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function showApprovalForm(int $registrationId): void
    {
        $this->approvingRegistrationId = $registrationId;
        $this->approvalNotes = '';
    }

    public function showRejectionForm(int $registrationId): void
    {
        $this->rejectingRegistrationId = $registrationId;
        $this->rejectionNotes = '';
    }

    public function cancelApproval(): void
    {
        $this->approvingRegistrationId = null;
        $this->approvalNotes = '';
    }

    public function cancelRejection(): void
    {
        $this->rejectingRegistrationId = null;
        $this->rejectionNotes = '';
    }

    public function approve(ProviderRegistration $registration): void
    {
        $this->authorize('update', $registration);

        $registration->approve(auth()->user(), $this->approvalNotes ?: null);

        session()->flash('message', 'Provider registration approved successfully.');

        $this->approvingRegistrationId = null;
        $this->approvalNotes = '';

        $this->dispatch('$refresh');
    }

    public function reject(ProviderRegistration $registration): void
    {
        $this->authorize('update', $registration);

        $this->validate([
            'rejectionNotes' => 'required|string|min:10',
        ], [
            'rejectionNotes.required' => 'Please provide a reason for rejection.',
            'rejectionNotes.min' => 'Please provide at least 10 characters explaining the rejection.',
        ]);

        $registration->reject(auth()->user(), $this->rejectionNotes);

        session()->flash('message', 'Provider registration rejected.');

        $this->rejectingRegistrationId = null;
        $this->rejectionNotes = '';

        $this->dispatch('$refresh');
    }

    public function mount(): void
    {
        $this->authorize('viewAny', ProviderRegistration::class);
    }

    public function render()
    {
        $registrationsQuery = ProviderRegistration::query()
            ->with(['space', 'submitter', 'reviewer', 'statusHistories.changedBy'])
            ->when($this->statusFilter !== 'all', fn ($query) => $query->where('status', $this->statusFilter))
            ->latest();

        $registrations = $registrationsQuery->paginate(20);

        $userSpaces = auth()->user()->spaces()->orderBy('name')->get();

        return view('livewire.provider-registrations.index', compact('registrations'))
            ->layout('layouts.sidebar', ['spaces' => $userSpaces]);
    }
}
