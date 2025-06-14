<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AuthorSeeder::class,
            PublisherSeeder::class,
            CategorySeeder::class,
            BookSeeder::class,
            UserSeeder::class,
        ]);

        $this->command->info('Library database seeded successfully!');
    }
}
