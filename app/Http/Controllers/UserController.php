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
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Buscar usuario por correo
    $user = \App\Models\User::where('email', $credentials['email'])->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    // Verificar si tiene perfil asociado
    $profile = $user->profile()->with('role')->first();

    if (!$profile || !$profile->role) {
        return response()->json(['message' => 'El usuario no tiene asignado un rol'], 401);
    }

    // Crear token si usas Sanctum (opcional)
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login exitoso',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
        'profile' => [
            'id' => $profile->id,
            'vereda' => $profile->vereda,
            'phone' => $profile->phone,
        ],
        'role' => [
            'id' => $profile->role->id,
            'name' => $profile->role->name_role,
        ],
        'token' => $token ?? null,
    ], 200);
}


}
