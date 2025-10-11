<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ably\AblyRest;

class AblyController extends Controller
{
    /**
     * Genera un token seguro para clientes (React, Android, etc.)
     */
    public function auth()
    {
        try {
            // Crear cliente Ably con tu API Key desde .env
            $ably = new AblyRest(env('KjUHEw.g4QvYw:6WIXqjibViuRbYbm-2-ZoLidx7EBnWaOd-6dXxCpDak'));

            // Crear token seguro (válido por defecto 1 hora)
            $tokenRequest = $ably->auth->createTokenRequest(['clientId' => 'frontend-client']);

            return response()->json($tokenRequest);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error generando token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Publica un mensaje en un canal específico
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'canal' => 'required|string',
            'mensaje' => 'required|string',
        ]);

        try {
            $ably = new AblyRest(env('KjUHEw.g4QvYw:6WIXqjibViuRbYbm-2-ZoLidx7EBnWaOd-6dXxCpDak'));
            $channel = $ably->channel($request->input('canal'));

            $channel->publish('alerta', $request->input('mensaje'));

            return response()->json([
                'success' => true,
                'message' => 'Mensaje enviado al canal ' . $request->input('canal')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error enviando mensaje: ' . $e->getMessage()
            ], 500);
        }
    }
}