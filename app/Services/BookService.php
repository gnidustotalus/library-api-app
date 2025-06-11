<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class BookService
{
    /**
     * Cache duration constants
     */
    private const CACHE_POPULAR_BOOKS = 3600; // 1 hour
    private const CACHE_BOOK_STATS = 3600; // 1 hour

    /**
     * Get popular books based on stock quantity with caching
     */
    public function getPopularBooks(int $limit = 10): Collection
    {
        $cacheKey = "popular_books_{$limit}";

        return Cache::remember($cacheKey, self::CACHE_POPULAR_BOOKS, function () use ($limit) {
            return Book::with(['author', 'publisher', 'category'])
                ->available()
                ->orderByDesc('stock_quantity')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Advanced search with business logic
     */
    public function searchBooks(array $criteria): Collection
    {
        $query = Book::with(['author', 'publisher', 'category']);

        if (!empty($criteria['search'])) {
            $query->search($criteria['search']);
        }

        if (isset($criteria['available_only']) && $criteria['available_only']) {
            $query->available();
        }

        if (!empty($criteria['language'])) {
            $query->byLanguage($criteria['language']);
        }

        if (!empty($criteria['category'])) {
            $query->whereHas('category', function ($q) use ($criteria) {
                if (is_numeric($criteria['category'])) {
                    $q->where('id', $criteria['category']);
                } else {
                    $q->where('slug', $criteria['category']);
                }
            });
        }

        if (!empty($criteria['author_id'])) {
            $query->where('author_id', $criteria['author_id']);
        }

        if (!empty($criteria['price_min'])) {
            $query->where('price', '>=', $criteria['price_min']);
        }
        if (!empty($criteria['price_max'])) {
            $query->where('price', '<=', $criteria['price_max']);
        }

        $sortBy = $criteria['sort_by'] ?? 'relevance';
        $direction = $criteria['sort_direction'] ?? 'desc';

        match ($sortBy) {
            'relevance' => $query->orderByDesc('stock_quantity')->orderBy('title'),
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'title' => $query->orderBy('title', $direction),
            default => $query->orderBy($sortBy, $direction),
        };

        return $query->get();
    }

    /**
     * Check book availability with business rules
     */
    public function checkAvailability(Book $book): array
    {
        return match (true) {
            !$book->is_available => [
                'available' => false,
                'status' => 'unavailable',
                'message' => 'This book is currently unavailable.',
            ],
            $book->stock_quantity <= 0 => [
                'available' => false,
                'status' => 'out_of_stock',
                'message' => 'This book is out of stock.',
            ],
            $book->stock_quantity <= 5 => [
                'available' => true,
                'status' => 'low_stock',
                'message' => 'Only few copies left!',
            ],
            default => [
                'available' => true,
                'status' => 'in_stock',
                'message' => 'Available for immediate delivery.',
            ],
        };
    }

    /**
     * Update stock with business validation
     */
    public function updateStock(Book $book, int $newQuantity, string $reason = 'manual_update'): bool
    {
        if ($newQuantity < 0) {
            throw new \InvalidArgumentException('Stock quantity cannot be negative');
        }

        DB::transaction(function () use ($book, $newQuantity, $reason) {
            $oldQuantity = $book->stock_quantity;
            $book->update(['stock_quantity' => $newQuantity]);

            \Log::info('Stock updated', [
                'book_id' => $book->id,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'reason' => $reason,
            ]);

            $this->clearCaches();
        });

        return true;
    }

    /**
     * Get book statistics with caching
     */
    public function getBookStatistics(): array
    {
        return Cache::remember('book_statistics', self::CACHE_BOOK_STATS, function () {
            return [
                'total_books' => Book::count(),
                'available_books' => Book::available()->count(),
                'out_of_stock' => Book::where('stock_quantity', 0)->count(),
                'low_stock' => Book::where('stock_quantity', '>', 0)
                                  ->where('stock_quantity', '<=', 5)->count(),
                'total_authors' => Author::count(),
                'total_categories' => Category::count(),
                'average_price' => Book::whereNotNull('price')->avg('price'),
                'languages' => Book::select('language', DB::raw('count(*) as count'))
                                  ->groupBy('language')
                                  ->pluck('count', 'language')
                                  ->toArray(),
            ];
        });
    }

    /**
     * Clear caches
     */
    private function clearCaches(): void
    {
        Cache::forget('book_statistics');
        Cache::forget('popular_books_10');
        Cache::forget('popular_books_5');
        Cache::forget('popular_books_20');
    }
} 