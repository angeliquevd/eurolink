<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Eurolink') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Flux Appearance -->
        @fluxAppearance

        <!-- Flux CSS -->
        <link rel="stylesheet" href="{{ asset('vendor/livewire/flux/dist/flux.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="min-h-dvh font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <x-banner />

        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <div class="hidden lg:flex lg:flex-shrink-0">
                <div class="flex flex-col w-64 border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <!-- Logo & User Section -->
                    <div class="flex flex-col flex-grow overflow-y-auto">
                        <!-- Logo -->
                        <div class="flex items-center justify-between flex-shrink-0 px-4 py-4 border-b border-gray-200 dark:border-gray-700">
                            <a href="{{ route('dashboard') }}" class="flex items-center">
                                <flux:heading size="lg" class="font-bold text-blue-600 dark:text-blue-400">
                                    Eurolink
                                </flux:heading>
                            </a>
                        </div>

                        <!-- Navigation -->
                        <nav class="flex-1 px-2 py-4 space-y-1">
                            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </a>

                            <a href="{{ route('inbox.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('inbox.*') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                Inbox
                            </a>

                            <a href="{{ route('search.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('search.*') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Search
                            </a>

                            <!-- Spaces Section -->
                            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between px-3 mb-2">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Spaces
                                    </span>
                                    <a href="{{ route('spaces.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </a>
                                </div>

                                @if(isset($spaces) && $spaces->count() > 0)
                                    <div class="space-y-1">
                                        @foreach($spaces as $space)
                                            <a href="{{ route('spaces.show', $space) }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('spaces.show') && request()->route('space')->id === $space->id ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                                <span class="w-2 h-2 mr-3 rounded-full {{ $space->isPublic() ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                                <span class="truncate">{{ $space->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                                        No spaces yet
                                    </div>
                                @endif
                            </div>

                            @if(auth()->user()->isEcStaff())
                                <!-- EC Staff Section -->
                                <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="px-3 mb-2">
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            EC Staff
                                        </span>
                                    </div>

                                    <a href="{{ route('provider-registrations.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('provider-registrations.*') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Provider Registrations
                                    </a>

                                    <a href="{{ route('announcements.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('announcements.*') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                        Announcements
                                    </a>
                                </div>
                            @endif
                        </nav>

                        <!-- User Section -->
                        <div class="flex-shrink-0 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between p-4">
                                <div class="flex items-center min-w-0">
                                    <div class="flex-shrink-0">
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                            <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                        @else
                                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900">
                                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                    {{ substr(Auth::user()->name, 0, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                                        </p>
                                    </div>
                                </div>
                                <flux:dropdown position="top" align="right">
                                    <flux:button variant="ghost" size="sm" icon-trailing="chevron-up" square inset="top bottom"></flux:button>

                                    <flux:menu>
                                        <flux:menu.item icon="user" href="{{ route('profile.show') }}">Profile</flux:menu.item>

                                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                            <flux:menu.item icon="key" href="{{ route('api-tokens.index') }}">API Tokens</flux:menu.item>
                                        @endif

                                        <flux:menu.separator />

                                        <flux:menu.item icon="arrow-right-start-on-rectangle" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Log Out
                                        </flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex flex-col flex-1 overflow-hidden">
                <div class="lg:hidden border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <div class="flex items-center justify-between px-4 py-3">
                        <flux:heading size="lg" class="font-bold text-blue-600 dark:text-blue-400">
                            Eurolink
                        </flux:heading>
                        <!-- Mobile menu button would go here -->
                    </div>
                </div>

                <!-- Main content -->
                <main class="flex-1 overflow-y-auto bg-gray-100 dark:bg-gray-900">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
        @fluxScripts
    </body>
</html>
