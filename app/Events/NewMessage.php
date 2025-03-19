<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(protected $user, protected Message $message)
    {
        //
    }

    public function broadcastWith(): array
    {

        return [
            'id' => $this->message->id,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'date' => now($this->user->timezone)->toDateTimeString(),
            'message' => $this->message->text,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('chat'),
        ];
    }
}
