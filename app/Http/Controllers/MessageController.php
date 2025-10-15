<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Ably\AblyRest;
use Exception;
use Illuminate\Support\Facades\Log; // ⬅️ AGREGAR

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

        try {
            $message = Message::create([
                'content' => $request->content,
                'is_read' => false,
                'sender_profile_id' => $request->sender_profile_id,
                'receiver_profile_id' => $request->receiver_profile_id,
                'is_admin_message' => false,
            ]);

            $ablyKey = config('services.ably.key') ?? env('ABLY_KEY');
            
            if (!$ablyKey) {
                throw new Exception('ABLY_KEY no configurada');
            }

            $ably = new AblyRest([
                'key' => $ablyKey
            ]);
            
            $channel = $ably->channels->get('CHAT');
            
            $channel->publish('new-message', [
                'id' => $message->id,
                'content' => $message->content,
                'sender_profile_id' => $message->sender_profile_id,
                'receiver_profile_id' => $message->receiver_profile_id,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at,
            ]);

            return response()->json([
                'success' => true,
                'data' => $message
            ], 201);

        } catch (Exception $e) {
            Log::error('Error en MessageController: ' . $e->getMessage()); // ✅ FUNCIONA
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
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