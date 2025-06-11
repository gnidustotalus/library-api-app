<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $authors = [
            [
                'name' => 'J.K. Rowling',
                'biography' => 'British author, best known for the Harry Potter fantasy series.',
                'birth_date' => '1965-07-31',
                'nationality' => 'British',
                'website' => 'https://www.jkrowling.com',
            ],
            [
                'name' => 'George Orwell',
                'biography' => 'English novelist and essayist, best known for "1984" and "Animal Farm".',
                'birth_date' => '1903-06-25',
                'nationality' => 'British',
                'website' => null,
            ],
            [
                'name' => 'Agatha Christie',
                'biography' => 'English writer known for her detective novels featuring Hercule Poirot and Miss Marple.',
                'birth_date' => '1890-09-15',
                'nationality' => 'British',
                'website' => null,
            ],
            [
                'name' => 'Stephen King',
                'biography' => 'American author of horror, supernatural fiction, suspense, crime, science-fiction, and fantasy novels.',
                'birth_date' => '1947-09-21',
                'nationality' => 'American',
                'website' => 'https://stephenking.com',
            ],
            [
                'name' => 'Isaac Asimov',
                'biography' => 'American writer and professor of biochemistry, known for his works of science fiction and popular science.',
                'birth_date' => '1920-01-02',
                'nationality' => 'American',
                'website' => null,
            ],
        ];

        foreach ($authors as $authorData) {
            Author::create($authorData);
        }
    }
}
