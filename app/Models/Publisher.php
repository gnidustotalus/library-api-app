<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publisher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'website',
        'established_year',
    ];

    protected $casts = [
        'established_year' => 'integer',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->established_year ? now()->year - $this->established_year : null;
    }

    public function getBooksCountAttribute(): int
    {
        return $this->books()->count();
    }
}
