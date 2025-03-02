<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendNotification implements ShouldQueue
{
    use Queueable;

    protected $user , $title, $body, $data, $messaging;

    public function __construct($user, $title, $body, $data = [])
    {
        $this->user = $user;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;

    }

    public function handle():void
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

        $this->messaging = $firebase->createMessaging();
        $notification = Notification::create($this->title, $this->body);
        $deviceToken = $this->user->fcm_token;
        if ($deviceToken != null) {
            $message = CloudMessage::withTarget('token', $this->user->fcm_token)
                ->withNotification($notification)->withData($this->data);
            $this->messaging->send($message);
        }

        //
    }
}
