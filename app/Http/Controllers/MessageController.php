<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Exceptions\ServerErrorException;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;
use Mockery\Exception;

class MessageController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function sendMessage(Request $request): \Illuminate\Http\JsonResponse
    {

        $validated = $request->validate([
            'message' => ['required', 'max:500'],
        ]);
        try {
            $message = Message::create([
                'text' => $validated['message'],
                'user_id' => auth()->id(),
            ]);
            broadcast(new NewMessage(\Auth::user(), $message));

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     */
    public function chat(): \Illuminate\Http\JsonResponse
    {

        try {
            $messages = Message::paginate(100);
            $messages->data = MessageResource::collection($messages);

            return response()->json([
                'status' => true,
                'messages' => $messages,
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
