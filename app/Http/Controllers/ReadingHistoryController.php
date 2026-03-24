<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadingHistoryController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'book_id'     => 'required|exists:books,id',
            'last_page'   => 'required|integer|min:1',
        ]);

        $book = Book::findOrFail($validated['book_id']);
        $percent = round(($validated['last_page'] / $book->total_pages) * 100, 2);


        ReadingHistory::updateOrCreate(
            ['user_id' => Auth::id(), 'book_id' => $validated['book_id']],
            [
                'last_page'        => $validated['last_page'],
                'total_pages'      => $book->total_pages,
                'progress_percent' => min($percent, 100),
                'status'           => $percent >= 100 ? 'finished' : 'reading',
                'last_read_at'     => now(),
            ]
        );
        return response()->json(['success' => true]);
    }
}
