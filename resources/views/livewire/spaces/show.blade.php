<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <flux:heading size="xl" class="mb-1">{{ $space->name }}</flux:heading>
                <div class="flex items-center gap-3">
                    <flux:subheading>{{ $space->description }}</flux:subheading>
                    <flux:badge :color="$space->isPublic() ? 'lime' : 'zinc'">
                        {{ ucfirst($space->visibility) }}
                    </flux:badge>
                </div>
            </div>
            <div class="flex gap-2">
                @if($space->hasProviderRegistration())
                    <flux:button href="{{ route('provider-registrations.create', $space) }}" variant="primary">
                        Register Platform
                    </flux:button>
                @endif
                @can('create', App\Models\Thread::class)
                    <flux:button wire:click="openCreateThread" variant="ghost">
                        New Thread
                    </flux:button>
                @endcan
            </div>
        </div>

        <!-- Platform Registration Info -->
        @if($space->hasProviderRegistration())
            <flux:card class="mb-6 bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <flux:heading size="lg" class="mb-2 text-blue-900 dark:text-blue-100">Platform Registration</flux:heading>
                        <flux:text class="text-blue-800 dark:text-blue-200 mb-3">
                            Register your platform with the European Commission. The registration will be reviewed by EC staff and you will be notified of the outcome.
                        </flux:text>
                        <flux:button href="{{ route('provider-registrations.create', $space) }}" variant="primary">
                            Start Registration →
                        </flux:button>
                    </div>
                </div>
            </flux:card>
        @endif

        <flux:card>
            <!-- Threads List -->
            <div class="space-y-4">
                @forelse($threads as $thread)
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0 last:pb-0">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                    {{ substr($thread->creator->name, 0, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('threads.show', $thread) }}" class="text-base font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                        {{ $thread->title }}
                                    </a>
                                    <div class="flex items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ $thread->creator->name }}</span>
                                        <span>•</span>
                                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <flux:badge color="zinc">
                                    {{ $thread->posts()->count() }} posts
                                </flux:badge>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <flux:heading size="lg" class="mb-2">No threads yet</flux:heading>
                        <flux:text class="text-gray-500 dark:text-gray-400 mb-4">
                            Be the first to start a discussion!
                        </flux:text>
                        @can('create', App\Models\Thread::class)
                            <flux:button wire:click="openCreateThread" variant="primary">
                                Create Thread
                            </flux:button>
                        @endcan
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($threads->hasPages())
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    {{ $threads->links() }}
                </div>
            @endif
        </flux:card>
    </div>

    <!-- Create Thread Modal -->
    <flux:modal name="create-thread" :open="$showCreateThread" wire:model="showCreateThread">
        <form wire:submit="createThread">
            <flux:heading size="lg" class="mb-4">Create New Thread</flux:heading>

            <flux:input
                wire:model="threadTitle"
                label="Thread Title"
                placeholder="Enter thread title..."
                required
            />

            @error('threadTitle')
                <flux:error>{{ $message }}</flux:error>
            @enderror

            <div class="mt-6 flex justify-end gap-2">
                <flux:button type="button" wire:click="closeCreateThread" variant="ghost">
                    Cancel
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Create Thread
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
