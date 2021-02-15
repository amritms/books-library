<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BooksController extends Controller
{
    /**
     * Display a listing of the all/available/checkout books.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status = 'ALL')
    {
        $books = Book::query();

        if(in_array($status = request('status'), ['CHECKED_OUT', 'AVAILABLE'])){
            $books->where('status', $status);
        }

        $books = $books->paginate();

        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 'AVAILABLE';

        $book = Book::create($data);

        return response()->json([
            'message' => 'Book Created Successfully.',
            'data' => new BookResource($book),
        ],
        Response::HTTP_CREATED);
    }
}
