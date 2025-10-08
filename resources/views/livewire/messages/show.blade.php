<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-4">
            <a href="{{ route('messages.inbox') }}" wire:navigate class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Inbox
            </a>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4">
                <p class="text-green-700 dark:text-green-300">{{ session('message') }}</p>
            </div>
        @endif

        <!-- Message Header -->
        <div class="mb-6">
            <flux:heading size="xl" class="mb-2">{{ $message->subject }}</flux:heading>
            @if($message->providerRegistration)
                <div class="flex items-center gap-2">
                    <flux:subheading>Related to registration:</flux:subheading>
                    <flux:badge color="zinc">{{ $message->providerRegistration->company_name }}</flux:badge>
                    <flux:badge :color="$message->providerRegistration->isPending() ? 'yellow' : ($message->providerRegistration->isApproved() ? 'lime' : 'red')">
                        {{ ucfirst($message->providerRegistration->status) }}
                    </flux:badge>
                </div>
            @endif
        </div>

        <!-- Conversation Thread -->
        <div class="space-y-4 mb-6">
            @foreach($conversation as $msg)
                <flux:card class="{{ $msg->sender_id === auth()->id() ? 'bg-blue-50 dark:bg-blue-950/20' : '' }}">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                    {{ substr($msg->sender->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <flux:heading size="sm" class="mb-1">
                                        {{ $msg->sender->name }}
                                        @if($msg->sender_id === auth()->id())
                                            <span class="text-xs text-gray-500">(You)</span>
                                        @endif
                                    </flux:heading>
                                    <flux:text class="text-xs text-gray-500">
                                        {{ $msg->created_at->format('M d, Y \a\t g:i A') }}
                                    </flux:text>
                                </div>
                                @if($msg->isUnread() && $msg->recipient_id === auth()->id())
                                    <flux:badge color="blue" size="sm">New</flux:badge>
                                @endif
                            </div>
                            <flux:text class="whitespace-pre-wrap">{{ $msg->body }}</flux:text>
                        </div>
                    </div>
                </flux:card>
            @endforeach
        </div>

        <!-- Reply Form -->
        <flux:card>
            <flux:heading size="lg" class="mb-4">Reply</flux:heading>

            <form wire:submit="sendReply">
                <flux:textarea
                    wire:model="replyBody"
                    label="Your message"
                    placeholder="Type your reply here..."
                    rows="6"
                    required
                />

                <div class="flex justify-end gap-3 mt-4">
                    <flux:button href="{{ route('messages.inbox') }}" wire:navigate variant="ghost">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Send Reply
                    </flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</div>
