<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Método para listar todos los usuarios
    public function index()
    {
  
    
      //  $users = User::included()->filter()->sort()->getOrPaginate();
       // return response()->json($users);
    
        // return response()->json (User::all());
        //return response()->json(User::all(), 200);
    //    $users = User::included()->filter()->get();
       // return response()->json($users);
        // Recupera todos los usuarios con relaciones incluidas
        $users = User::included()->get();

        // Aplica filtros si están definidos en la query y vuelve a obtener usuarios
    //    $users = User::included()->filter()->get();

        // Retorna la lista de usuarios en formato JSON
       return response()->json($users);

       
    }

    /**
     * Guarda un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
            'email'     => 'required|max:255',
            'location'  => 'required|max:255',
            'password'  => 'required|max:255',
        ]);

        $user = User::create($request->all());

        return response()->json($user);
    }

    /**
     * Muestra un usuario específico por su ID.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Actualiza los datos de un usuario existente 
   public function update(Request $request, $id)
{
    $request->validate([
        'firstname' => 'required|max:255',
        'lastname'  => 'required|max:255',
        'email'     => 'required|max:255',
        'location'  => 'required|max:255',
        'password'  => 'required|max:255',
    ]);

    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    $user->update($request->all());

    return response()->json($user, 200);
}

    // Elimina un usuario de la base de datos

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado'], 200);
    }

}
