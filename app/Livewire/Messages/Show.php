<?php

namespace App\Livewire\Messages;

use App\Models\Message;
use Illuminate\Support\Str;
use Livewire\Component;

class Show extends Component
{
    public Message $message;

    public string $replyBody = '';

    public function mount(Message $message): void
    {
        $this->authorize('view', $message);

        $this->message = $message;

        // Mark as read when viewing
        if ($message->recipient_id === auth()->id()) {
            $message->markAsRead();
        }
    }

    public function sendReply()
    {
        $this->validate([
            'replyBody' => 'required|string|min:10',
        ]);

        $reply = Message::create([
            'provider_registration_id' => $this->message->provider_registration_id,
            'sender_id' => auth()->id(),
            'recipient_id' => $this->message->sender_id,
            'subject' => 'Re: '.Str::replaceFirst('Re: ', '', $this->message->subject),
            'body' => $this->replyBody,
        ]);

        // If the sender is the provider and the registration was rejected, reopen it for review
        if ($this->message->providerRegistration
            && $this->message->providerRegistration->isRejected()
            && auth()->id() === $this->message->providerRegistration->submitted_by) {

            $this->message->providerRegistration->reopenForReview(
                auth()->user(),
                'Provider replied with additional information'
            );

            session()->flash('message', 'Reply sent successfully. Your registration has been reopened for review.');
        } else {
            session()->flash('message', 'Reply sent successfully.');
        }

        $this->replyBody = '';

        return $this->redirect(route('messages.inbox'), navigate: true);
    }

    public function render()
    {
        $conversation = Message::query()
            ->where('provider_registration_id', $this->message->provider_registration_id)
            ->with(['sender', 'recipient'])
            ->orderBy('created_at')
            ->get();

        $userSpaces = auth()->user()->spaces()->orderBy('name')->get();

        return view('livewire.messages.show', compact('conversation'))
            ->layout('layouts.sidebar', ['spaces' => $userSpaces]);
    }
}
