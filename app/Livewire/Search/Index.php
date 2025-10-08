<?php

namespace App\Livewire\Search;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $userSpaces = auth()->user()->spaces()->orderBy('name')->get();

        return view('livewire.search.index')
            ->layout('layouts.sidebar', ['spaces' => $userSpaces]);
    }
}
