<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-1">Announcements</flux:heading>
                <flux:subheading>Important updates from EC Staff</flux:subheading>
            </div>
            @can('create', App\Models\Announcement::class)
                <flux:button href="{{ route('announcements.create') }}" variant="primary">
                    New Announcement
                </flux:button>
            @endcan
        </div>

        <!-- Coming Soon -->
        <flux:card>
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                <flux:heading size="xl" class="mb-2">Coming Soon</flux:heading>
                <flux:text class="text-gray-500 dark:text-gray-400 mb-6">
                    Announcements will allow EC Staff to broadcast important updates to space members with delivery tracking.
                </flux:text>
                <flux:button href="{{ route('dashboard') }}" variant="primary">
                    Back to Dashboard
                </flux:button>
            </div>
        </flux:card>
    </div>
</div>
