<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <flux:heading size="xl" class="mb-1">Inbox</flux:heading>
            <flux:subheading>Messages and notifications about your provider registrations</flux:subheading>
        </div>

        <!-- Filter -->
        <div class="mb-6">
            <flux:tabs wire:model.live="filter">
                <flux:tab name="all">All ({{ auth()->user()->receivedMessages()->count() }})</flux:tab>
                <flux:tab name="unread">Unread ({{ auth()->user()->unreadMessages()->count() }})</flux:tab>
            </flux:tabs>
        </div>

        <!-- Messages List -->
        <div class="space-y-3">
            @forelse($messages as $message)
                <a href="{{ route('messages.show', $message) }}" wire:key="message-{{ $message->id }}">
                    <flux:card class="hover:border-zinc-400 dark:hover:border-zinc-500 transition-colors {{ $message->isUnread() ? 'bg-blue-50 dark:bg-blue-950/20 border-blue-200 dark:border-blue-900' : '' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    @if($message->isUnread())
                                        <div class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0"></div>
                                    @endif
                                    <flux:heading size="lg" class="truncate {{ $message->isUnread() ? 'font-bold' : '' }}">
                                        {{ $message->subject }}
                                    </flux:heading>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    <span>From: {{ $message->sender->name }}</span>
                                    <span>•</span>
                                    <span>{{ $message->created_at->diffForHumans() }}</span>
                                    @if($message->providerRegistration)
                                        <span>•</span>
                                        <flux:badge color="zinc" size="sm">
                                            {{ $message->providerRegistration->company_name }}
                                        </flux:badge>
                                    @endif
                                </div>
                                <flux:text class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">
                                    {{ Str::limit($message->body, 200) }}
                                </flux:text>
                            </div>
                            @if($message->isUnread())
                                <flux:badge color="blue" size="sm">New</flux:badge>
                            @endif
                        </div>
                    </flux:card>
                </a>
            @empty
                <flux:card>
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <flux:heading size="lg" class="mb-2">No messages</flux:heading>
                        <flux:text class="text-gray-500 dark:text-gray-400">
                            {{ $filter === 'unread' ? 'You have no unread messages.' : 'Your inbox is empty.' }}
                        </flux:text>
                    </div>
                </flux:card>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($messages->hasPages())
            <div class="mt-6">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
