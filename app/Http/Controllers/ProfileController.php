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
            'message' => 'Profile created successfully',
            'data' => $profile
        ], 201);
    }

    public function show($id)
    {
        $profile = Profile::included()->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $profile
        ], 200);
    }

    public function update(Request $request, Profile $profile)
    {
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
            'message' => 'Profile updated successfully',
            'data' => $profile
        ], 200);
    }

    public function destroy(Profile $profile)
    {
        $profile->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile deleted successfully'
        ], 200);
    }
}