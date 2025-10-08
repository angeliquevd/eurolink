<?php

namespace App\Livewire\Spaces;

use App\Models\Space;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $q = '';

    public function render()
    {
        $spaces = Space::query()
            ->when($this->q, fn ($query) => $query->where('name', 'like', "%{$this->q}%"))
            ->orderBy('name')
            ->paginate(12);

        $userSpaces = auth()->user()->spaces()->orderBy('name')->get();

        return view('livewire.spaces.index', compact('spaces'))
            ->layout('layouts.sidebar', ['spaces' => $userSpaces]);
    }
}
