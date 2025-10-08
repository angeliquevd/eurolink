<?php

namespace App\Livewire\Threads;

use App\Models\Thread;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Thread $thread;
    public string $postBody = '';

    public function mount(Thread $thread): void
    {
        $this->authorize('view', $thread);
        $this->thread = $thread;
    }

    public function createPost(): void
    {
        $this->authorize('create', \App\Models\Post::class);

        $validated = $this->validate([
            'postBody' => 'required|string|min:3|max:5000',
        ]);

        $this->thread->posts()->create([
            'body' => $validated['postBody'],
            'created_by' => auth()->id(),
        ]);

        $this->postBody = '';
        $this->resetPage();
    }

    public function render()
    {
        $posts = $this->thread->posts()
            ->with('creator')
            ->orderBy('created_at')
            ->paginate(20);

        $userSpaces = auth()->user()->spaces()->orderBy('name')->get();

        return view('livewire.threads.show', compact('posts'))
            ->layout('layouts.sidebar', ['spaces' => $userSpaces]);
    }
}
