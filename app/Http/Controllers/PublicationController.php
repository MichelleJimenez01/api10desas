<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publications = Publication::included()
            ->filter()
            ->sort()
            ->getOrPaginate();

        return response()->json([
            'status' => 'success',
            'data' => $publications
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'profile_id' => 'required|exists:profiles,id',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id'
        ]);

        $publication = Publication::create($validated);

        // Sincronizar categorías si se envían
        if (!empty($validated['categories'])) {
            $publication->categories()->sync($validated['categories']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Publication created successfully',
            'data' => $publication->load('categories', 'profile')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $publication = Publication::included()->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $publication
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|max:255',
            'content' => 'sometimes|required',
            'profile_id' => 'sometimes|required|exists:profiles,id',
            'categories' => 'sometimes|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $publication->update($validated);

        // Si se envían categorías, sincronizamos
        if (isset($validated['categories'])) {
            $publication->categories()->sync($validated['categories']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Publication updated successfully',
            'data' => $publication->load('categories', 'profile')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
        $publication->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Publication deleted successfully'
        ], 200);
    }
}
