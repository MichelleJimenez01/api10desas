<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ably\AblyRest;
use Exception;

class AblyController extends Controller
{
    /**
     * Obtiene la clave de Ably desde las variables de entorno
     */
    private function getAblyKey()
    {
        return env('ABLY_API_KEY') ?? env('ABLY_KEY');
    }

    /**
     * Genera un token seguro para clientes (React, Android, etc.)
     */
    public function auth()
    {
        try {
            $ablyKey = $this->getAblyKey();
            
            if (!$ablyKey) {
                throw new Exception('ABLY_KEY no configurada');
            }

            $ably = new AblyRest([
                'key' => $ablyKey
            ]);

            $tokenRequest = $ably->auth->createTokenRequest([
                'clientId' => 'frontend-client'
            ]);

            return response()->json($tokenRequest);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error generando token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Publica un mensaje en un canal específico de Ably
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'canal' => 'required|string',
            'mensaje' => 'required|string',
        ]);

        try {
            $ablyKey = $this->getAblyKey();
            
            if (!$ablyKey) {
                throw new Exception('ABLY_KEY no configurada');
            }

            $ably = new AblyRest([
                'key' => $ablyKey
            ]);
            
            $channel = $ably->channels->get($request->input('canal'));
            
            $channel->publish('alerta', [
                'mensaje' => $request->input('mensaje'),
                'timestamp' => now()->toISOString(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mensaje enviado al canal ' . $request->input('canal')
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error enviando mensaje: ' . $e->getMessage()
            ], 500);
        }
    }
}