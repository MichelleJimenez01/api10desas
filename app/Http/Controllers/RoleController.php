<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $roles= Role::included()->get();

        $roles= Role::included()->filter()->sort()->get();

        $roles= Role::included()->filter()->sort()->getOrPaginate();

        // // $roles= Role::include()->filter()->get();
        // $roles = Role::all();
        //retora todos los roles 
        return response()->json($roles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //almacenamiento de nuevo rol 
        $request->validate([
            'name_role'=> 'required|max:255',
        ]);

        $role = Role::create($request->all());

        return response()->json($role);
    }

    /**
     * Display the specified resource.
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //nos permite observar los registros mediante el id
        $role= Role::findOrFail($id);
        $role = Role::with(['profiles'])->findOrFail($id);
        return response()->json($role);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //proceso de actualizar registro
        $request->validate([
            'name_role' => 'required|max255',
        ]);
        
        $role->update($request->all());
        return $role;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //proceso de eliminar registro
        $role->delete();
        return $role;
    }
}
