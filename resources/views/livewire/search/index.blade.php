<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <flux:heading size="xl" class="mb-1">Search</flux:heading>
            <flux:subheading>Find threads, posts, and users across all your spaces</flux:subheading>
        </div>

        <!-- Coming Soon -->
        <flux:card>
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <flux:heading size="xl" class="mb-2">Coming Soon</flux:heading>
                <flux:text class="text-gray-500 dark:text-gray-400 mb-6">
                    Global search will help you find threads, posts, users, and announcements across all your spaces instantly.
                </flux:text>
                <flux:button href="{{ route('dashboard') }}" variant="primary">
                    Back to Dashboard
                </flux:button>
            </div>
        </flux:card>
    </div>
</div>
