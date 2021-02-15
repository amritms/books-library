<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id,status,CHECKED_OUT'
        ]);

        auth()->user()->books()->attach([
            $request->book_id => ['action' => 'CHECKIN']
        ]);

        $book = Book::find($request->book_id);
        $book->update(['status' => 'AVAILABLE']);

        return response()->json([
            'message' => 'Book Checked in Successfully.',
            'data' => new BookResource($book)
        ], 200);
    }
}
