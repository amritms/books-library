<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\UserActionLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckinController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'book_ids' => 'required|array',
            'book_ids.*' => 'required|exists:books,id,status,CHECKED_OUT'
        ]);

        try {
            DB::beginTransaction();

            $user_id = auth()->id();
            $now = now();
            $insert_data = collect($data['book_ids'])->map(function ($book_id) use ($user_id, $now) {
                return [
                    'user_id' => $user_id,
                    'book_id' => $book_id,
                    'action' => 'CHECKIN',
                    'created_at' => $now,
                ];
            })->toArray();

            UserActionLog::insert($insert_data);

            $books = tap(Book::whereIn('id', $data['book_ids']), function ($query) {
                $query->update(['status' => 'AVAILABLE']);
            })->get();

            DB::commit();

            return response()->json([
                'message' => 'Book Checked in Successfully.',
                'data' => BookResource::collection($books)
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            DB::rollBack();

            Log::error(
                sprintf('Book could not be checked in for %s due to %s', request('book_id'), $exception->getMessage()),
                ['stackTrace' => $exception->getTraceAsString()]
            );

            return response()->json([
                'message' => $exception->getMessage(),
                'code' => $exception->getCode()
            ], 400);
        }

    }
}
