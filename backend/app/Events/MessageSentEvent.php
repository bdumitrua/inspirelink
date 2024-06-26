<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use App\Models\NoSQL\Message;

class MessageSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $chatId;
    public Message $message;

    /**
     * Create a new event instance.
     */
    public function __construct(int $chatId, Message $message)
    {
        $this->chatId = $chatId;
        $this->message = $message;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('chat.' . $this->chatId);
    }

    public function broadcastQueue(): string
    {
        return 'websockets';
    }

    public function broadcastWith()
    {
        return [
            'chatId' => $this->chatId,
            'message' => $this->message,
        ];
    }
}
