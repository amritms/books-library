<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id,status,AVAILABLE'
        ]);

        auth()->user()->books()->attach([
            $request->book_id => ['action' => 'CHECKOUT']
        ]);

        $book = Book::find($request->book_id);
        $book->update(['status' => 'CHECKED_OUT']);

        return response()->json([
            'message' => 'Book Checked out Successfully.',
            'data' => new BookResource($book)
        ], 200);
    }
}
