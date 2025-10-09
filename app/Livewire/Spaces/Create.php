<?php

namespace App\Livewire\Spaces;

use App\Models\Space;
use App\Models\SpaceMembership;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';

    public string $slug = '';

    public string $visibility = 'public';

    public string $description = '';

    public bool $enable_provider_registration = false;

    public function updatedName(): void
    {
        $this->slug = Str::slug($this->name);
    }

    public function submit(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:spaces,slug|alpha_dash',
            'visibility' => 'required|in:public,private',
            'description' => 'nullable|string',
            'enable_provider_registration' => 'boolean',
        ]);

        $space = Space::create($validated);

        // Add creator as owner
        SpaceMembership::create([
            'user_id' => auth()->id(),
            'space_id' => $space->id,
            'role_in_space' => 'owner',
        ]);

        session()->flash('message', 'Space created successfully!');

        $this->redirect(route('spaces.show', $space), navigate: true);
    }

    public function render()
    {
        $user = auth()->user();
        $spaces = $user->spaces()->orderBy('name')->get();

        return view('livewire.spaces.create')
            ->layout('layouts.sidebar', ['spaces' => $spaces]);
    }
}
