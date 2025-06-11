<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Publisher;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $publishers = [
            [
                'name' => 'Penguin Random House',
                'address' => '1745 Broadway, New York, NY 10019, USA',
                'website' => 'https://www.penguinrandomhouse.com',
                'established_year' => 2013,
            ],
            [
                'name' => 'HarperCollins',
                'address' => '195 Broadway, New York, NY 10007, USA',
                'website' => 'https://www.harpercollins.com',
                'established_year' => 1989,
            ],
            [
                'name' => 'Simon & Schuster',
                'address' => '1230 Avenue of the Americas, New York, NY 10020, USA',
                'website' => 'https://www.simonandschuster.com',
                'established_year' => 1924,
            ],
            [
                'name' => 'Hachette Book Group',
                'address' => '1290 Avenue of the Americas, New York, NY 10104, USA',
                'website' => 'https://www.hachettebookgroup.com',
                'established_year' => 2006,
            ],
            [
                'name' => 'Oxford University Press',
                'address' => 'Great Clarendon Street, Oxford OX2 6DP, UK',
                'website' => 'https://global.oup.com',
                'established_year' => 1586,
            ],
        ];

        foreach ($publishers as $publisherData) {
            Publisher::create($publisherData);
        }
    }
}
