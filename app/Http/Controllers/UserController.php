<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listar todos los usuarios
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Registrar nuevo usuario (CON HASH)
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
            'email'     => 'required|email|unique:users,email',
            'location'  => 'required|max:255',
            'password'  => 'required|min:6|max:255',
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'location'  => $request->location,
            'password'  => Hash::make($request->password), // ✅ HASHEADO
        ]);

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'user' => $user
        ], 201);
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
            'password'  => 'nullable|min:6|max:255', // Opcional al actualizar
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $data = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'location'  => $request->location,
        ];

        // Solo actualizar contraseña si se envía
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password); // ✅ HASHEADO
        }

        $user->update($data);

        return response()->json([
            'message' => 'Usuario actualizado',
            'user' => $user
        ], 200);
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

    // 🔐 LOGIN SIN BCRYPT (Solo comparación directa)
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Buscar usuario por correo
            $user = User::where('email', $credentials['email'])->first();

            if (!$user) {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }

            // ⚠️ COMPARACIÓN DIRECTA DE CONTRASEÑAS (sin hash)
            if ($user->password !== $credentials['password']) {
                return response()->json(['message' => 'Contraseña incorrecta'], 401);
            }

            // Verificar si tiene perfil asociado
            $profile = $user->profile()->with('role')->first();

            if (!$profile || !$profile->role) {
                return response()->json(['message' => 'El usuario no tiene asignado un rol'], 401);
            }

            return response()->json([
                'message' => 'Login exitoso',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->firstname . ' ' . $user->lastname,
                    'email' => $user->email,
                    'role_id' => $profile->role->id, // ✅ Agregado para el frontend
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
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}