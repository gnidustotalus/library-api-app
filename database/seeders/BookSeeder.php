<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $books = [
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'isbn' => '9780747532699',
                'description' => 'The first novel in the Harry Potter series.',
                'pages' => 223,
                'published_at' => '1997-06-26',
                'language' => 'en',
                'price' => 12.99,
                'stock_quantity' => 25,
                'is_available' => true,
                'author_id' => 1, // J.K. Rowling
                'publisher_id' => 1, // Penguin Random House
                'category_id' => 1, // Fiction
            ],
            [
                'title' => '1984',
                'isbn' => '9780451524935',
                'description' => 'Dystopian social science fiction novel.',
                'pages' => 328,
                'published_at' => '1949-06-08',
                'language' => 'en',
                'price' => 13.99,
                'stock_quantity' => 15,
                'is_available' => true,
                'author_id' => 2, // George Orwell
                'publisher_id' => 2, // HarperCollins
                'category_id' => 2, // Science Fiction
            ],
            [
                'title' => 'Murder on the Orient Express',
                'isbn' => '9780062693662',
                'description' => 'A detective novel featuring Hercule Poirot.',
                'pages' => 256,
                'published_at' => '1934-01-01',
                'language' => 'en',
                'price' => 11.99,
                'stock_quantity' => 8,
                'is_available' => true,
                'author_id' => 3, // Agatha Christie
                'publisher_id' => 3, // Simon & Schuster
                'category_id' => 3, // Mystery
            ],
            [
                'title' => 'The Shining',
                'isbn' => '9780307743657',
                'description' => 'Horror novel about the Overlook Hotel.',
                'pages' => 447,
                'published_at' => '1977-01-28',
                'language' => 'en',
                'price' => 14.99,
                'stock_quantity' => 12,
                'is_available' => true,
                'author_id' => 4, // Stephen King
                'publisher_id' => 4, // Hachette Book Group
                'category_id' => 4, // Horror
            ],
            [
                'title' => 'Foundation',
                'isbn' => '9780553293357',
                'description' => 'Science fiction novel about psychohistory.',
                'pages' => 244,
                'published_at' => '1951-05-01',
                'language' => 'en',
                'price' => 12.99,
                'stock_quantity' => 20,
                'is_available' => true,
                'author_id' => 5, // Isaac Asimov
                'publisher_id' => 5, // Oxford University Press
                'category_id' => 2, // Science Fiction
            ],
            [
                'title' => 'And Then There Were None',
                'isbn' => '9780062073488',
                'description' => 'Mystery novel about ten strangers on an island.',
                'pages' => 264,
                'published_at' => '1939-11-06',
                'language' => 'en',
                'price' => 10.99,
                'stock_quantity' => 18,
                'is_available' => true,
                'author_id' => 3, // Agatha Christie
                'publisher_id' => 2, // HarperCollins
                'category_id' => 3, // Mystery
            ],
            [
                'title' => 'It',
                'isbn' => '9781501142970',
                'description' => 'Horror novel about a malevolent entity.',
                'pages' => 1138,
                'published_at' => '1986-09-15',
                'language' => 'en',
                'price' => 18.99,
                'stock_quantity' => 6,
                'is_available' => true,
                'author_id' => 4, // Stephen King
                'publisher_id' => 3, // Simon & Schuster
                'category_id' => 4, // Horror
            ],
            [
                'title' => 'I, Robot',
                'isbn' => '9780553382563',
                'description' => 'Collection of nine science fiction short stories.',
                'pages' => 224,
                'published_at' => '1950-12-02',
                'language' => 'en',
                'price' => 11.99,
                'stock_quantity' => 14,
                'is_available' => true,
                'author_id' => 5, // Isaac Asimov
                'publisher_id' => 1, // Penguin Random House
                'category_id' => 2, // Science Fiction
            ],
            [
                'title' => 'Animal Farm',
                'isbn' => '9780451526342',
                'description' => 'Allegorical novella about farm animals.',
                'pages' => 112,
                'published_at' => '1945-08-17',
                'language' => 'en',
                'price' => 9.99,
                'stock_quantity' => 22,
                'is_available' => true,
                'author_id' => 2, // George Orwell
                'publisher_id' => 5, // Oxford University Press
                'category_id' => 1, // Fiction
            ],
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }
    }
}
