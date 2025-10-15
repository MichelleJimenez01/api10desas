<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Ably\AblyRest;

class MessageController extends Controller
{
     public function index()
    {
        $messages = Message::query()
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'sender_profile_id' => 'required|integer|exists:profiles,id',
            'receiver_profile_id' => 'required|integer|exists:profiles,id',
        ]);
        $message = Message::create([
            'content' => $request->content,
            'is_read' => false,
            'sender_profile_id' => $request->sender_profile_id,
            'receiver_profile_id' => $request->receiver_profile_id,
            'is_admin_message' => false,
        ]);

        // 🔴 Publicar en Ably
        $ably = new AblyRest(config('services.ably.key'));
        $channel = $ably->channel('chat');
        $channel->publish('new-message', $message);

        return response()->json($message, 201);
    }

    public function show($id)
    {
        $message = Message::included()->findOrFail($id);
        return response()->json($message);
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        $message->update($request->only(['content', 'is_read']));

        return response()->json($message);
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return response()->json(null, 204);
    }
}
