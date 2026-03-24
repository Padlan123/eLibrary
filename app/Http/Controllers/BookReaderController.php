<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingHistory;
use Illuminate\Support\Facades\Auth;

class BookReaderController extends Controller
{
    public function show(Book $book)
    {
        $history = ReadingHistory::firstOrCreate(
            ['user_id' => Auth::id(), 'book_id' => $book->id],
            ['last_page' => 1, 'status' => 'reading', 'last_read_at' => now()]
        );

        return view('books.reader', compact('book', 'history'));
    }
}
