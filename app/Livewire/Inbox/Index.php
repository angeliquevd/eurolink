<?php

namespace App\Livewire\Inbox;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $userSpaces = auth()->user()->spaces()->orderBy('name')->get();

        return view('livewire.inbox.index')
            ->layout('layouts.sidebar', ['spaces' => $userSpaces]);
    }
}
