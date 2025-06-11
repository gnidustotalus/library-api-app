<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fiction',
                'description' => 'Literary works of imaginative narration, in prose or verse.',
            ],
            [
                'name' => 'Science Fiction',
                'description' => 'Fiction dealing with futuristic concepts and advanced science and technology.',
            ],
            [
                'name' => 'Mystery',
                'description' => 'Fiction involving mysterious events, crimes, or puzzles to be solved.',
            ],
            [
                'name' => 'Horror',
                'description' => 'Fiction intended to frighten, unsettle, create suspense, or create a dark atmosphere.',
            ],
            [
                'name' => 'Non-Fiction',
                'description' => 'Literary works based on facts, real events, and real people.',
            ],
            [
                'name' => 'Biography',
                'description' => 'A detailed description of a person\'s life written by someone else.',
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
