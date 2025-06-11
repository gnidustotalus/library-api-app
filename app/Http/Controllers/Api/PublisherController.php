<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublisherResource;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Publisher::withCount('books');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
        }

        if ($request->boolean('include_books')) {
            $query->with('books');
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        if (in_array($sortBy, ['name', 'created_at', 'books_count', 'established_year'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $publishers = $query->paginate($request->get('per_page', 15));

        return PublisherResource::collection($publishers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'established_year' => 'nullable|integer|min:1000|max:' . date('Y'),
        ]);

        $publisher = Publisher::create($validated);

        return (new PublisherResource($publisher))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher, Request $request): PublisherResource
    {
        // Load books if requested
        if ($request->boolean('include_books')) {
            $publisher->load('books');
        }

        $publisher->loadCount('books');

        return new PublisherResource($publisher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher): PublisherResource
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'established_year' => 'nullable|integer|min:1000|max:' . date('Y'),
        ]);

        $publisher->update($validated);

        return new PublisherResource($publisher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher): JsonResponse
    {
        if ($publisher->books()->exists()) {
            return response()->json([
                'message' => 'Cannot delete publisher with existing books.',
                'error' => 'PUBLISHER_HAS_BOOKS',
            ], 422);
        }

        $publisher->delete();

        return response()->json([
            'message' => 'Publisher deleted successfully.',
        ], 200);
    }
}
