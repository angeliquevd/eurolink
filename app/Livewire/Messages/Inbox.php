<?php

namespace App\Livewire\Messages;

use App\Models\Message;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Inbox extends Component
{
    use WithPagination;

    public string $filter = 'all'; // all, unread

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function getListeners()
    {
        return [
            "echo-private:user.".auth()->id().",MessageSent" => 'notifyNewMessage',
        ];
    }

    public function notifyNewMessage($event): void
    {
        $this->dispatch('$refresh');
    }

    public function markAsRead(Message $message): void
    {
        $this->authorize('view', $message);

        $message->markAsRead();

        $this->dispatch('$refresh');
    }

    public function render()
    {
        $messagesQuery = auth()->user()
            ->receivedMessages()
            ->with(['sender', 'providerRegistration'])
            ->when($this->filter === 'unread', fn ($query) => $query->whereNull('read_at'))
            ->latest();

        $messages = $messagesQuery->paginate(20);

        $userSpaces = auth()->user()->spaces()->orderBy('name')->get();

        return view('livewire.messages.inbox', compact('messages'))
            ->layout('layouts.sidebar', ['spaces' => $userSpaces]);
    }
}
