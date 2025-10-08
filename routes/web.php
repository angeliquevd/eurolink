<?php

use Illuminate\Support\Facades\Route;

Route::get('/', App\Livewire\Landing::class)->name('landing');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');

    // Spaces
    Route::get('/spaces', App\Livewire\Spaces\Index::class)->name('spaces.index');
    Route::get('/spaces/create', App\Livewire\Spaces\Create::class)->name('spaces.create');
    Route::get('/spaces/{space:slug}', App\Livewire\Spaces\Show::class)->name('spaces.show');

    // Provider Registrations
    Route::get('/spaces/{space:slug}/register-provider', App\Livewire\ProviderRegistrations\Create::class)->name('provider-registrations.create');
    Route::get('/provider-registrations', App\Livewire\ProviderRegistrations\Index::class)->name('provider-registrations.index');

    // Threads
    Route::get('/threads/{thread}', App\Livewire\Threads\Show::class)->name('threads.show');

    // Announcements
    Route::get('/announcements', App\Livewire\Announcements\Index::class)->name('announcements.index');
    Route::get('/announcements/create', App\Livewire\Announcements\Create::class)->name('announcements.create');

    // Messages
    Route::get('/messages', App\Livewire\Messages\Inbox::class)->name('messages.inbox');
    Route::get('/messages/{message}', App\Livewire\Messages\Show::class)->name('messages.show');

    // Inbox (Notifications)
    Route::get('/inbox', App\Livewire\Inbox\Index::class)->name('inbox.index');

    // Search
    Route::get('/search', App\Livewire\Search\Index::class)->name('search.index');
});
