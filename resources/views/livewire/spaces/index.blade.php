<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-1">Spaces</flux:heading>
                <flux:subheading>Browse and join discussion spaces</flux:subheading>
            </div>
            @can('create', App\Models\Space::class)
                <flux:button href="{{ route('spaces.create') }}" variant="primary">
                    New Space
                </flux:button>
            @endcan
        </div>

        <!-- Search -->
        <div class="mb-6">
            <flux:input
                wire:model.live.debounce.300ms="q"
                placeholder="Search spaces..."
                class="max-w-md"
            />
        </div>

        <!-- Spaces Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($spaces as $space)
                <flux:card>
                    <div class="mb-3 flex items-start justify-between">
                        <flux:heading size="lg">{{ $space->name }}</flux:heading>
                        <flux:badge :color="$space->isPublic() ? 'lime' : 'zinc'">
                            {{ ucfirst($space->visibility) }}
                        </flux:badge>
                    </div>
                    <flux:text class="line-clamp-2 mb-4 text-gray-600 dark:text-gray-400">
                        {{ $space->description }}
                    </flux:text>
                    <div class="flex items-center justify-between">
                        <flux:text class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $space->threads()->count() }} threads
                        </flux:text>
                        <flux:button href="{{ route('spaces.show', $space) }}" variant="primary">
                            Open
                        </flux:button>
                    </div>
                </flux:card>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <flux:heading size="lg" class="mb-2">No spaces found</flux:heading>
                    <flux:text class="text-gray-500 dark:text-gray-400">
                        {{ $q ? 'Try a different search term' : 'No spaces available yet' }}
                    </flux:text>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($spaces->hasPages())
            <div class="mt-6">
                {{ $spaces->links() }}
            </div>
        @endif
    </div>
</div>
