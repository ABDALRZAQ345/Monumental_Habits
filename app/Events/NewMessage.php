<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class NewMessage implements  ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct(public  $userId, public string $message)
    {
        //
    }


    public function broadcastOn(): array
    {
        return [
            new Channel('chat'),
        ];
    }
}
