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
        $request->validate([
            'book_id' => 'required|exists:books,id,status,CHECKED_OUT'
        ]);

        try{
            DB::beginTransaction();

            $user_action_log = UserActionLog::where('book_id', $request->book_id)->latest('id')->first();

            UserActionLog::create([
                'book_id' => $request->book_id,
                'user_id' => $user_action_log->user_id,
                'action' => 'CHECKIN'
            ]);

            $book = Book::find($request->book_id);
            $book->update(['status' => 'AVAILABLE']);

            DB::commit();

            return response()->json([
                'message' => 'Book Checked in Successfully.',
                'data' => new BookResource($book)
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
