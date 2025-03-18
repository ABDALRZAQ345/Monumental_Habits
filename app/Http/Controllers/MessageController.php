<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {


        $message = $request->input('message');

        Message::create([
            'text' => $message,
            'user_id' => auth()->id()
        ]);
        //  broadcast the event of sending new message
        broadcast(new NewMessage(auth()->id(),$message));

        return response()->json(['success' => true, 'message' => 'Message sent successfully!']);
    }
}
