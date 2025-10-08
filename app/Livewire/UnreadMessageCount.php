<?php

namespace App\Livewire;

use Livewire\Component;

class UnreadMessageCount extends Component
{
    public function getListeners()
    {
        return [
            "echo-private:user.".auth()->id().",MessageSent" => '$refresh',
        ];
    }

    public function render()
    {
        $count = auth()->user()->unreadMessages()->count();

        return view('livewire.unread-message-count', compact('count'));
    }
}
