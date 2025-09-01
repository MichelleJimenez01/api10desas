<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Lista todos los mensajes con filtros, orden y relaciones dinámicas.
     * Ejemplo: /api/v1/messages?include=sender,receiver&filter[is_read]=0&sort=-created_at&perPage=10
     */
    public function index()
    {
        $messages = Message::included()
            ->filter()
            ->sort()
            ->getOrPaginate();

        return response()->json([
            'message' => 'Mensajes obtenidos correctamente',
            'data' => $messages
        ], 200);
    }

    /**
     * Guarda un nuevo mensaje en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content'             => 'required|string',
            'is_read'             => 'boolean',
            'sender_profile_id'   => 'required|exists:profiles,id',
            'receiver_profile_id' => 'required|exists:profiles,id',
        ]);

        $message = Message::create($request->all());

        return response()->json([
            'message' => 'Mensaje creado correctamente',
            'data' => $message->load(['sender', 'receiver'])
        ], 201);
    }

    /**
     * Muestra un mensaje específico por su ID, incluyendo relaciones dinámicas.
     */
    public function show($id)
    {
        $message = Message::included()->find($id);

        if (!$message) {
            return response()->json(['message' => 'Mensaje no encontrado'], 404);
        }

        return response()->json([
            'message' => 'Mensaje obtenido correctamente',
            'data' => $message
        ], 200);
    }

    /**
     * Actualiza un mensaje existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content'             => 'sometimes|string',
            'is_read'             => 'sometimes|boolean',
            'sender_profile_id'   => 'sometimes|exists:profiles,id',
            'receiver_profile_id' => 'sometimes|exists:profiles,id',
        ]);

        $message = Message::find($id);

        if (!$message) {
            return response()->json(['message' => 'Mensaje no encontrado'], 404);
        }

        $message->update($request->all());

        return response()->json([
            'message' => 'Mensaje actualizado correctamente',
            'data' => $message->load(['sender', 'receiver'])
        ], 200);
    }

    /**
     * Elimina un mensaje existente.
     */
    public function destroy($id)
    {
        $message = Message::find($id);

        if (!$message) {
            return response()->json(['message' => 'Mensaje no encontrado'], 404);
        }

        $message->delete();

        return response()->json(['message' => 'Mensaje eliminado correctamente'], 200);
    }
}