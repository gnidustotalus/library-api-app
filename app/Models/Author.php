<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'biography',
        'birth_date',
        'nationality',
        'website',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['name'],
        );
    }

    public function isAlive(): bool
    {
        return match (true) {
            is_null($this->birth_date) => true,
            $this->birth_date->addYears(120) > now() => true,
            default => false,
        };
    }

    public function getBooksCountAttribute(): int
    {
        return $this->books()->count();
    }
}
