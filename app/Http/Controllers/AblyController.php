<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ably\AblyRest;

class AblyController extends Controller
{
    public function auth()
    {
        try {
            $ably = new AblyRest([
                'key' => env('ABLY_KEY')
            ]);

            $tokenRequest = $ably->auth->createTokenRequest([
                'clientId' => 'frontend-client'
            ]);

            return response()->json($tokenRequest);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error generando token: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'canal' => 'required|string',
            'mensaje' => 'required|string',
        ]);

        try {
            $ably = new AblyRest([
                'key' => env('ABLY_KEY')
            ]);
            
            $channel = $ably->channels->get($request->input('canal'));
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