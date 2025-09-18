<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    
    public function index()
    {
        $profiles = Profile::included();
            // ->filter()
            // ->sort()
            // ->getOrPaginate();

        return response()->json($profiles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6',
            'photo'    => 'nullable|string',
            'user_id'  => 'required|exists:users,id',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $profile = Profile::create($request->all());

        return response()->json($profile, 201);
    }

    public function show($id)
    {
        $profile = Profile::included()->findOrFail($id);
        return response()->json($profile);
    }

    public function update(Request $request, Profile $profile)
    {
        $request->validate([
            'password' => 'sometimes|string|min:6',
            'photo'    => 'nullable|string',
            'user_id'  => 'sometimes|exists:users,id',
            'role_id'  => 'sometimes|exists:roles,id',
        ]);

        $profile->update($request->all());

        return response()->json($profile);
    }

    public function destroy(Profile $profile)
    {
        $profile->delete();
        return response()->json($profile);
    }

}
