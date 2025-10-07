<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    
   public function index()
    {
        
        $profiles = Profile::included()
        ->filter()
        ->sort()
        ->getOrPaginate();
        return response()->json([
            'status' => 'success',
            'data' => $profiles
        ], 200);
    }

    /**
     * Guarda un nuevo perfil.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'photo'    => 'nullable|string',
            'phone'    => 'nullable|integer',
            'vereda'   => 'required|string',
            'user_id'  => 'required|exists:users,id',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $profile = Profile::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Perfil creado correctamente',
            'data' => $profile
        ], 201);
    }

    /**
     * Muestra un perfil específico por su ID.
     */
    public function show($id)
    {
        $profile = Profile::included()->find($id);

        if (!$profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'Perfil no encontrado'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $profile
        ], 200);
    }

    /**
     * Actualiza un perfil existente.
     */
    public function update(Request $request, $id)
    {
        $profile = Profile::find($id);

        if (!$profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'Perfil no encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'photo'    => 'nullable|string',
            'phone'    => 'nullable|integer',
            'vereda'   => 'required|string',
            'user_id'  => 'sometimes|exists:users,id',
            'role_id'  => 'sometimes|exists:roles,id',
        ]);

        $profile->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Perfil actualizado correctamente',
            'data' => $profile
        ], 200);
    }

    /**
     * Elimina un perfil.
     */
    public function destroy($id)
    {
        $profile = Profile::find($id);

        if (!$profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'Perfil no encontrado'
            ], 404);
        }

        $profile->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Perfil eliminado correctamente'
        ], 200);
    }
}