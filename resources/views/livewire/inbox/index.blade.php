<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <flux:heading size="xl" class="mb-1">Inbox</flux:heading>
            <flux:subheading>Your mentions, replies, and announcements</flux:subheading>
        </div>

        <!-- Coming Soon -->
        <flux:card>
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <flux:heading size="xl" class="mb-2">Coming Soon</flux:heading>
                <flux:text class="text-gray-500 dark:text-gray-400 mb-6">
                    Your personalized inbox will show mentions, replies to your posts, and announcements from your spaces.
                </flux:text>
                <flux:button href="{{ route('dashboard') }}" variant="primary">
                    Back to Dashboard
                </flux:button>
            </div>
        </flux:card>
    </div>
</div>
