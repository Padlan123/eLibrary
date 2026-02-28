<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('books')->insert([
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'publisher' => 'Scribner',
                'publication_year' => 1925,
                'summary' => 'A novel about the American dream and the decadence of the Jazz Age.',
                'subscription' => false,
                'cover_file_name' => null,
                'pdf_file_name' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'publisher' => 'J.B. Lippincott & Co.',
                'publication_year' => 1960,
                'summary' => 'A story of racial injustice and moral growth in the Deep South.',
                'subscription' => true,
                'cover_file_name' => null,
                'pdf_file_name' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
