<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <flux:heading size="xl" class="mb-2">Welcome back, {{ auth()->user()->name }}</flux:heading>
            <flux:subheading>Here's what's happening in your spaces</flux:subheading>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
            <flux:card>
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <flux:heading size="lg">{{ $spaces->count() }}</flux:heading>
                        <flux:text class="text-sm text-gray-500 dark:text-gray-400">Spaces</flux:text>
                    </div>
                </div>
            </flux:card>

            <flux:card>
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <flux:heading size="lg">{{ $recentThreads->count() }}</flux:heading>
                        <flux:text class="text-sm text-gray-500 dark:text-gray-400">Active Threads</flux:text>
                    </div>
                </div>
            </flux:card>

            <flux:card>
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <flux:heading size="lg">{{ $recentPosts->count() }}</flux:heading>
                        <flux:text class="text-sm text-gray-500 dark:text-gray-400">Your Posts</flux:text>
                    </div>
                </div>
            </flux:card>

            <flux:card>
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <flux:heading size="lg">0</flux:heading>
                        <flux:text class="text-sm text-gray-500 dark:text-gray-400">Unread</flux:text>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Recent Threads -->
            <div class="lg:col-span-2">
                <flux:card>
                    <div class="flex items-center justify-between mb-4">
                        <flux:heading size="lg">Recent Threads</flux:heading>
                        <flux:button href="{{ route('spaces.index') }}" variant="ghost" size="sm">
                            View all spaces
                        </flux:button>
                    </div>

                    @if($recentThreads->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentThreads as $thread)
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
                                                <a href="{{ route('threads.show', $thread) }}" class="text-sm font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $thread->title }}
                                                </a>
                                                <div class="flex items-center gap-2 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                    <a href="{{ route('spaces.show', $thread->space) }}" class="hover:text-gray-700 dark:hover:text-gray-300">
                                                        {{ $thread->space->name }}
                                                    </a>
                                                    <span>•</span>
                                                    <span>{{ $thread->creator->name }}</span>
                                                    <span>•</span>
                                                    <span>{{ $thread->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <flux:badge color="zinc" size="sm">
                                                {{ $thread->posts()->count() }} posts
                                            </flux:badge>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <flux:heading size="lg" class="mt-4 text-gray-900 dark:text-white">No threads yet</flux:heading>
                            <flux:text class="mt-2 text-gray-500 dark:text-gray-400">
                                Join a space to start discussions
                            </flux:text>
                            <flux:button href="{{ route('spaces.index') }}" variant="primary" class="mt-4">
                                Browse Spaces
                            </flux:button>
                        </div>
                    @endif
                </flux:card>
            </div>

            <!-- Sidebar: Your Spaces & Activity -->
            <div class="space-y-6">
                <!-- Your Spaces -->
                <flux:card>
                    <div class="flex items-center justify-between mb-4">
                        <flux:heading size="lg">Your Spaces</flux:heading>
                        <flux:button href="{{ route('spaces.index') }}" variant="ghost" size="sm">
                            Browse
                        </flux:button>
                    </div>

                    @if($spaces->count() > 0)
                        <div class="space-y-3">
                            @foreach($spaces->take(5) as $space)
                                <a href="{{ route('spaces.show', $space) }}" class="flex items-center gap-3 p-2 -mx-2 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <span class="flex-shrink-0 w-2 h-2 rounded-full {{ $space->isPublic() ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                    <div class="flex-1 min-w-0">
                                        <flux:text class="font-medium truncate">{{ $space->name }}</flux:text>
                                        <flux:text class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $space->threads()->count() }} threads
                                        </flux:text>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        @if($spaces->count() > 5)
                            <div class="pt-3 mt-3 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('spaces.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                    View all {{ $spaces->count() }} spaces →
                                </a>
                            </div>
                        @endif
                    @else
                        <flux:text class="text-gray-500 dark:text-gray-400">
                            You haven't joined any spaces yet.
                        </flux:text>
                    @endif
                </flux:card>

                <!-- Recent Activity -->
                <flux:card>
                    <flux:heading size="lg" class="mb-4">Your Recent Posts</flux:heading>

                    @if($recentPosts->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentPosts as $post)
                                <div class="pb-3 border-b border-gray-200 dark:border-gray-700 last:border-0 last:pb-0">
                                    <a href="{{ route('threads.show', $post->thread) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                        {{ $post->thread->title }}
                                    </a>
                                    <flux:text class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $post->created_at->diffForHumans() }} in {{ $post->thread->space->name }}
                                    </flux:text>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <flux:text class="text-gray-500 dark:text-gray-400">
                            You haven't posted anything yet.
                        </flux:text>
                    @endif
                </flux:card>

                <!-- Quick Actions -->
                @can('create', App\Models\Thread::class)
                    <flux:card>
                        <flux:heading size="lg" class="mb-4">Quick Actions</flux:heading>
                        <div class="space-y-2">
                            <flux:button href="{{ route('spaces.index') }}" variant="primary" class="w-full justify-center">
                                Browse Spaces
                            </flux:button>
                            @can('create', App\Models\Space::class)
                                <flux:button href="{{ route('spaces.create') }}" variant="ghost" class="w-full justify-center">
                                    Create Space
                                </flux:button>
                            @endcan
                        </div>
                    </flux:card>
                @endcan
            </div>
        </div>
    </div>
</div>
