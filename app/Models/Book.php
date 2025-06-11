<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isbn',
        'description',
        'pages',
        'published_at',
        'language',
        'price',
        'stock_quantity',
        'is_available',
        'author_id',
        'publisher_id',
        'category_id',
    ];

    protected $casts = [
        'published_at' => 'date',
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'stock_quantity' => 'integer',
        'pages' => 'integer',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true)->where('stock_quantity', '>', 0);
    }

    public function scopeByLanguage(Builder $query, string $language): Builder
    {
        return $query->where('language', $language);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('isbn', 'like', "%{$search}%")
              ->orWhereHas('author', fn (Builder $query) => 
                  $query->where('name', 'like', "%{$search}%")
              );
        });
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => 
                $attributes['price'] ? '$' . number_format($attributes['price'], 2) : 'N/A',
        );
    }

    public function isInStock(): bool
    {
        return match (true) {
            !$this->is_available => false,
            $this->stock_quantity > 0 => true,
            default => false,
        };
    }

    public function getAvailabilityStatusAttribute(): string
    {
        return match (true) {
            !$this->is_available => 'unavailable',
            $this->stock_quantity > 10 => 'in_stock',
            $this->stock_quantity > 0 => 'low_stock',
            default => 'out_of_stock',
        };
    }
}
