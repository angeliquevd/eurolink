<?php

namespace App\Livewire;

use Livewire\Component;

class Landing extends Component
{
    public string $name = '';
    public string $organization = '';
    public string $email = '';
    public string $role = '';
    public bool $showRequestAccess = false;

    public function openRequestAccess(): void
    {
        $this->showRequestAccess = true;
        $this->reset(['name', 'organization', 'email', 'role']);
    }

    public function closeRequestAccess(): void
    {
        $this->showRequestAccess = false;
    }

    public function submitAccessRequest(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string|in:provider,ec_staff,observer',
        ]);

        // TODO: Send email notification to admin or store in database
        session()->flash('message', 'Thank you! Your access request has been submitted.');

        $this->closeRequestAccess();
    }

    public function render()
    {
        return view('livewire.landing')
            ->layout('layouts.guest');
    }
}
