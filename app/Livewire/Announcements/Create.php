<?php

namespace App\Livewire\Announcements;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        $user = auth()->user();
        $spaces = $user->spaces()->orderBy('name')->get();

        return view('livewire.announcements.create')
            ->layout('layouts.sidebar', ['spaces' => $spaces]);
    }
}
