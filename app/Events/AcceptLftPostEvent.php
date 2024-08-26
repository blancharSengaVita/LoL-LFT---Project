<?php

namespace App\Events;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptLftPostEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $message;
    public $conversation;

    /**
     * Create a new event instance.
     */
    public function __construct($user_id, $message, $conversation_id)
    {
        $newMessage = new Message();
        $newMessage->conversation_id = $conversation_id;
        $newMessage->user_id = $user_id;
        $newMessage->message = $message;
        $newMessage->color = 'green';
        $newMessage->save();

        $this->message = $message;
        $this->user_id = $user_id;
        $this->conversation = $conversation_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('Accept-lft-invitation'),
        ];
    }

}
