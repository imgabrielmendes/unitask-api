<?php

namespace App\Events\Message;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\User\User;
use App\Models\Message\Chat;
use App\Models\Message\Message;

use function Laravel\Prompts\error;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $chat;
    public $receiver;
    public function __construct(User $user, Message $message, Chat $chat, User $receiver)
    {
        $this->user = $user;
        $this->message = $message;
        $this->chat = $chat;
        $this->receiver = $receiver;
    }

    public function broadcastWith(): array
    {
        return [
            'user' => $this->user,
            'message' => $this->message,
            'chat' => $this->chat,
            'receiver' => $this->receiver,
        ];
    }

    public function broadcastOn()
    {
        error_log('Broadcasting message to: ' . $this->receiver->id);
        return new PrivateChannel('chat.' . $this->receiver->id);
    }
    
}