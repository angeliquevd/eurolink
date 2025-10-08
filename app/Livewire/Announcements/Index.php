<?php

namespace App\Livewire\Announcements;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $userSpaces = auth()->user()->spaces()->orderBy('name')->get();

        return view('livewire.announcements.index')
            ->layout('layouts.sidebar', ['spaces' => $userSpaces]);
    }
}
