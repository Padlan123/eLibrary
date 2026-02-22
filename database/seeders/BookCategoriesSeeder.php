<?php

namespace Database\Seeders;


use App\Models\BookCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookCategoriesSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('book_categories')->insert([
            ['book_id' => 1, 'category_id' => 1],
            ['book_id' => 1, 'category_id' => 3],
            ['book_id' => 2, 'category_id' => 1],
            ['book_id' => 2, 'category_id' => 2],
        ]);
    }
}
