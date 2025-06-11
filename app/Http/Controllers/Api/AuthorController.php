<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Author::query();

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('biography', 'like', "%{$search}%")
                  ->orWhere('nationality', 'like', "%{$search}%");
        }

        if ($nationality = $request->get('nationality')) {
            $query->where('nationality', $nationality);
        }

        $query->withCount('books');

        if ($request->boolean('include_books')) {
            $query->with('books');
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        if (in_array($sortBy, ['name', 'created_at', 'books_count', 'birth_date'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $authors = $query->paginate($request->get('per_page', 15));

        return AuthorResource::collection($authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'nationality' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
        ]);

        $author = Author::create($validated);

        return (new AuthorResource($author))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author, Request $request): AuthorResource
    {
        if ($request->boolean('include_books')) {
            $author->load('books');
        }

        $author->loadCount('books');

        return new AuthorResource($author);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author): AuthorResource
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'biography' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'nationality' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
        ]);

        $author->update($validated);

        return new AuthorResource($author);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author): JsonResponse
    {
        if ($author->books()->exists()) {
            return response()->json([
                'message' => 'Cannot delete author with existing books.',
                'error' => 'AUTHOR_HAS_BOOKS',
            ], 422);
        }

        $author->delete();

        return response()->json([
            'message' => 'Author deleted successfully.',
        ], 200);
    }
}
