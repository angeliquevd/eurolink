<?php

namespace App\Livewire\Spaces;

use App\Models\Space;
use Livewire\Component;

class Show extends Component
{
    public Space $space;
    public bool $showCreateThread = false;
    public string $threadTitle = '';

    public function mount(Space $space): void
    {
        $this->authorize('view', $space);
        $this->space = $space;
    }

    public function openCreateThread(): void
    {
        $this->authorize('create', \App\Models\Thread::class);
        $this->showCreateThread = true;
        $this->threadTitle = '';
    }

    public function closeCreateThread(): void
    {
        $this->showCreateThread = false;
        $this->threadTitle = '';
    }

    public function createThread(): void
    {
        $this->authorize('create', \App\Models\Thread::class);

        $validated = $this->validate([
            'threadTitle' => 'required|string|min:3|max:255',
        ]);

        $thread = $this->space->threads()->create([
            'title' => $validated['threadTitle'],
            'created_by' => auth()->id(),
        ]);

        $this->redirect(route('threads.show', $thread), navigate: true);
    }

    public function render()
    {
        $threads = $this->space->threads()
            ->with('creator')
            ->latest()
            ->paginate(20);

        $userSpaces = auth()->user()->spaces()->orderBy('name')->get();

        return view('livewire.spaces.show', compact('threads'))
            ->layout('layouts.sidebar', ['spaces' => $userSpaces]);
    }
}
