<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <flux:heading size="xl" class="mb-1">Platform Registrations</flux:heading>
            <flux:subheading>Review and manage platform registration submissions</flux:subheading>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4">
                <p class="text-green-700 dark:text-green-300">{{ session('message') }}</p>
            </div>
        @endif

        <!-- Filter -->
        <div class="mb-6">
            <flux:tabs wire:model.live="statusFilter">
                <flux:tab name="all">All ({{ \App\Models\ProviderRegistration::count() }})</flux:tab>
                <flux:tab name="pending">Pending ({{ \App\Models\ProviderRegistration::where('status', 'pending')->count() }})</flux:tab>
                <flux:tab name="approved">Approved ({{ \App\Models\ProviderRegistration::where('status', 'approved')->count() }})</flux:tab>
                <flux:tab name="rejected">Rejected ({{ \App\Models\ProviderRegistration::where('status', 'rejected')->count() }})</flux:tab>
            </flux:tabs>
        </div>

        <!-- Registrations List -->
        <div class="space-y-4">
            @forelse($registrations as $registration)
                <flux:card>
                    <div class="flex items-start justify-between gap-6">
                        <div class="flex-1 min-w-0">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <flux:heading size="lg" class="mb-1">{{ $registration->company_name }}</flux:heading>
                                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                        <span>{{ $registration->company_country }}</span>
                                        <span>•</span>
                                        <span>{{ $registration->space->name }}</span>
                                        <span>•</span>
                                        <span>Submitted by {{ $registration->submitter->name }}</span>
                                        <span>•</span>
                                        <span>{{ $registration->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <flux:badge :color="$registration->isPending() ? 'yellow' : ($registration->isApproved() ? 'lime' : 'red')">
                                    {{ ucfirst($registration->status) }}
                                </flux:badge>
                            </div>

                            <!-- Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <flux:text class="text-sm font-medium text-gray-700 dark:text-gray-300">Company Information</flux:text>
                                    <flux:text class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        @if($registration->company_registration_number)
                                            Reg: {{ $registration->company_registration_number }}<br>
                                        @endif
                                        {{ $registration->company_address }}<br>
                                        @if($registration->company_website)
                                            <a href="{{ $registration->company_website }}" target="_blank" class="text-blue-600 hover:underline">{{ $registration->company_website }}</a>
                                        @endif
                                    </flux:text>
                                </div>

                                <div>
                                    <flux:text class="text-sm font-medium text-gray-700 dark:text-gray-300">Contact Person</flux:text>
                                    <flux:text class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $registration->contact_person_name }} - {{ $registration->contact_person_title }}<br>
                                        {{ $registration->contact_person_email }}<br>
                                        @if($registration->contact_person_phone)
                                            {{ $registration->contact_person_phone }}
                                        @endif
                                    </flux:text>
                                </div>

                                <div class="md:col-span-2">
                                    <flux:text class="text-sm font-medium text-gray-700 dark:text-gray-300">Platform Services</flux:text>
                                    <flux:text class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $registration->ai_systems_description }}
                                    </flux:text>
                                    @if($registration->ai_system_types)
                                        <div class="flex gap-2 mt-2">
                                            @foreach($registration->ai_system_types as $type)
                                                <flux:badge color="zinc">{{ ucwords(str_replace('_', ' ', $type)) }}</flux:badge>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                @if($registration->intended_use_cases)
                                    <div class="md:col-span-2">
                                        <flux:text class="text-sm font-medium text-gray-700 dark:text-gray-300">Intended Use Cases</flux:text>
                                        <flux:text class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $registration->intended_use_cases }}
                                        </flux:text>
                                    </div>
                                @endif

                                @if($registration->additional_notes)
                                    <div class="md:col-span-2">
                                        <flux:text class="text-sm font-medium text-gray-700 dark:text-gray-300">Additional Notes</flux:text>
                                        <flux:text class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $registration->additional_notes }}
                                        </flux:text>
                                    </div>
                                @endif
                            </div>

                            <!-- Status History -->
                            @if($registration->statusHistories->count() > 0)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <flux:text class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status History</flux:text>
                                    <div class="space-y-2">
                                        @foreach($registration->statusHistories->sortByDesc('created_at') as $history)
                                            <div class="text-sm text-gray-600 dark:text-gray-400 pl-3 border-l-2 {{ $history->to_status === 'approved' ? 'border-green-500' : ($history->to_status === 'rejected' ? 'border-red-500' : 'border-yellow-500') }}">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium">{{ ucfirst($history->from_status ?? 'new') }} → {{ ucfirst($history->to_status) }}</span>
                                                    <span>•</span>
                                                    <span>{{ $history->created_at->format('M d, Y g:i A') }}</span>
                                                </div>
                                                @if($history->changedBy)
                                                    <div>By: {{ $history->changedBy->name }}</div>
                                                @endif
                                                @if($history->notes)
                                                    <div class="mt-1 text-xs">{{ $history->notes }}</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Review Info -->
                            @if($registration->reviewed_at)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <flux:text class="text-sm text-gray-600 dark:text-gray-400">
                                        <strong>Latest Review by:</strong> {{ $registration->reviewer->name }} on {{ $registration->reviewed_at->format('M d, Y') }}
                                        @if($registration->review_notes)
                                            <br><strong>Notes:</strong> {{ $registration->review_notes }}
                                        @endif
                                    </flux:text>
                                </div>
                            @endif

                            <!-- Actions -->
                            @if($registration->isPending() && auth()->user()->isEcStaff())
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    @if($approvingRegistrationId === $registration->id)
                                        <!-- Approval Form -->
                                        <div class="space-y-3">
                                            <flux:textarea
                                                wire:model="approvalNotes"
                                                label="Approval Notes (Optional)"
                                                placeholder="Add any notes or comments for the provider..."
                                                rows="3"
                                            />
                                            <div class="flex gap-2">
                                                <flux:button wire:click="approve({{ $registration->id }})" variant="primary">
                                                    Confirm Approval
                                                </flux:button>
                                                <flux:button wire:click="cancelApproval" variant="ghost">
                                                    Cancel
                                                </flux:button>
                                            </div>
                                        </div>
                                    @elseif($rejectingRegistrationId === $registration->id)
                                        <!-- Rejection Form -->
                                        <div class="space-y-3">
                                            <flux:textarea
                                                wire:model="rejectionNotes"
                                                label="Rejection Reason (Required)"
                                                placeholder="Explain what needs to be modified or what additional information is required..."
                                                rows="4"
                                                required
                                            />
                                            <div class="flex gap-2">
                                                <flux:button wire:click="reject({{ $registration->id }})" variant="danger">
                                                    Confirm Rejection
                                                </flux:button>
                                                <flux:button wire:click="cancelRejection" variant="ghost">
                                                    Cancel
                                                </flux:button>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Action Buttons -->
                                        <div class="flex gap-2">
                                            <flux:button wire:click="showApprovalForm({{ $registration->id }})" variant="primary">
                                                Approve
                                            </flux:button>
                                            <flux:button wire:click="showRejectionForm({{ $registration->id }})" variant="danger">
                                                Reject
                                            </flux:button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </flux:card>
            @empty
                <flux:card>
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <flux:heading size="lg" class="mb-2">No registrations found</flux:heading>
                        <flux:text class="text-gray-500 dark:text-gray-400">
                            {{ $statusFilter === 'all' ? 'No platform registrations have been submitted yet.' : 'No ' . $statusFilter . ' registrations found.' }}
                        </flux:text>
                    </div>
                </flux:card>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($registrations->hasPages())
            <div class="mt-6">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>
