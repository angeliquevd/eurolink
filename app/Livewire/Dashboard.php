<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        // Get user's spaces
        $spaces = $user->spaces()
            ->orderBy('name')
            ->get();

        // Get recent threads from user's spaces
        $recentThreads = \App\Models\Thread::query()
            ->whereIn('space_id', $spaces->pluck('id'))
            ->with(['space', 'creator'])
            ->latest()
            ->limit(10)
            ->get();

        // Get recent posts from user
        $recentPosts = \App\Models\Post::query()
            ->where('created_by', $user->id)
            ->with(['thread.space'])
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.dashboard', [
            'spaces' => $spaces,
            'recentThreads' => $recentThreads,
            'recentPosts' => $recentPosts,
        ])->layout('layouts.sidebar', ['spaces' => $spaces]);
    }
}
