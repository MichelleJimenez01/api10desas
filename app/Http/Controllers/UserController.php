<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Listar todos los usuarios
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Registrar nuevo usuario (sin Hash::make)
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
            'email'     => 'required|email|unique:users,email',
            'location'  => 'required|max:255',
            'password'  => 'required|max:255',
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'location'  => $request->location,
            'password'  => $request->password, // guardamos en texto plano
        ]);

        return response()->json($user, 201);
    }

    // Mostrar un usuario
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $request->validate([
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
            'email'     => 'required|email',
            'location'  => 'required|max:255',
            'password'  => 'required|max:255',
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->update([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'location'  => $request->location,
            'password'  => $request->password, // texto plano
        ]);

        return response()->json($user, 200);
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado'], 200);
    }

public function login(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Buscar usuario por email
        $user = User::where('email', $request->email)->first();

        // Verificar usuario y contraseña
        if (!$user || $request->password !== $user->password) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Validar que tenga un rol
        if (!$user->role_id) {
            return response()->json(['message' => 'El usuario no tiene asignado un rol'], 403);
        }

        // Generar un "token" falso para mantener compatibilidad con tu frontend
        $token = base64_encode(random_bytes(32));

        // Retornar usuario con su rol
        return response()->json([
            'message' => 'Login exitoso',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'location' => $user->location,
                'role_id' => $user->role_id, // 👈 se envía el rol aquí
            ],
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error interno del servidor',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}
