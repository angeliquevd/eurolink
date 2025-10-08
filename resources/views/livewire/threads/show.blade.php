<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-4">
            <a href="{{ route('spaces.show', $thread->space) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to {{ $thread->space->name }}
            </a>
        </div>

        <!-- Thread Header -->
        <div class="mb-6">
            <flux:heading size="xl" class="mb-2">{{ $thread->title }}</flux:heading>
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <span>Started by {{ $thread->creator->name }}</span>
                <span>•</span>
                <span>{{ $thread->created_at->diffForHumans() }}</span>
                <span>•</span>
                <span>{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}</span>
            </div>
        </div>

        <!-- Reply Form -->
        @can('create', App\Models\Post::class)
            <flux:card class="mb-6">
                <form wire:submit="createPost">
                    <flux:heading size="sm" class="mb-3">Reply to this thread</flux:heading>

                    <flux:textarea
                        wire:model="postBody"
                        rows="4"
                        placeholder="Write your reply..."
                        required
                    />

                    @error('postBody')
                        <flux:error class="mt-2">{{ $message }}</flux:error>
                    @enderror

                    <div class="mt-3 flex justify-end">
                        <flux:button type="submit" variant="primary">
                            Post Reply
                        </flux:button>
                    </div>
                </form>
            </flux:card>
        @endcan

        <!-- Posts List -->
        <div class="space-y-4">
            @forelse($posts as $post)
                <flux:card>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                    {{ substr($post->creator->name, 0, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="mb-2 flex items-center justify-between">
                                <div>
                                    <flux:heading size="sm">{{ $post->creator->name }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $post->created_at->diffForHumans() }}
                                    </flux:text>
                                </div>
                            </div>
                            <flux:text class="whitespace-pre-line">{{ $post->body }}</flux:text>
                        </div>
                    </div>
                </flux:card>
            @empty
                <flux:card>
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <flux:heading size="lg" class="mb-2">No posts yet</flux:heading>
                        <flux:text class="text-gray-500 dark:text-gray-400">Be the first to reply!</flux:text>
                    </div>
                </flux:card>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
