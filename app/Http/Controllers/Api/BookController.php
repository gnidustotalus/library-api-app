<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Book::with(['author', 'publisher', 'category']);

        if ($search = $request->get('search')) {
            $query->search($search);
        }
        if ($request->boolean('available_only')) {
            $query->available();
        }

        if ($language = $request->get('language')) {
            $query->byLanguage($language);
        }

        if ($category = $request->get('category')) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category)->orWhere('id', $category);
            });
        }

        $sortBy = $request->get('sort_by', 'title');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        if (in_array($sortBy, ['title', 'price', 'published_at', 'created_at', 'stock_quantity'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $books = $query->paginate($request->get('per_page', 15));

        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn|max:20',
            'description' => 'nullable|string',
            'pages' => 'nullable|integer|min:1',
            'published_at' => 'nullable|date',
            'language' => 'string|max:10|in:en,es,fr,de,it,pl',
            'price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'integer|min:0',
            'is_available' => 'boolean',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book = Book::create($validated);
        $book->load(['author', 'publisher', 'category']);

        return (new BookResource($book))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): BookResource
    {
        $book->load(['author', 'publisher', 'category']);

        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book): BookResource
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'isbn' => 'sometimes|required|string|max:20|unique:books,isbn,' . $book->id,
            'description' => 'nullable|string',
            'pages' => 'nullable|integer|min:1',
            'published_at' => 'nullable|date',
            'language' => 'sometimes|string|max:10|in:en,es,fr,de,it,pl',
            'price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'is_available' => 'sometimes|boolean',
            'author_id' => 'sometimes|required|exists:authors,id',
            'publisher_id' => 'sometimes|required|exists:publishers,id',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $book->update($validated);
        $book->load(['author', 'publisher', 'category']);

        return new BookResource($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully.',
        ], 200);
    }

    /**
     * Get popular books (using BookService with caching)
     */
    public function popular(Request $request): AnonymousResourceCollection
    {
        $limit = $request->get('limit', 10);
        $books = $this->bookService->getPopularBooks($limit);

        return BookResource::collection($books);
    }

    /**
     * Advanced search (using BookService)
     */
    public function search(Request $request): AnonymousResourceCollection
    {
        $criteria = $request->validate([
            'search' => 'nullable|string|max:255',
            'available_only' => 'boolean',
            'language' => 'nullable|string|max:10',
            'category' => 'nullable|string',
            'author_id' => 'nullable|exists:authors,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'sort_by' => 'nullable|string|in:relevance,price_low,price_high,title',
            'sort_direction' => 'nullable|string|in:asc,desc',
        ]);

        $books = $this->bookService->searchBooks($criteria);

        return BookResource::collection($books);
    }
}
