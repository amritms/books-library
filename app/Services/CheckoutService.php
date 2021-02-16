<?php

Namespace App\Services;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\UserActionLog;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutService {
    /**
     * @param $data
     * @return Illuminate\Database\Eloquent\Collection|null
     */
    public function __invoke($data)
    {
        try {
            $user_id = auth()->id();
            $now = now();

            $insert_data = collect($data['book_ids'])->map(function ($book_id) use ($user_id, $now) {
                return [
                    'user_id' => $user_id,
                    'book_id' => $book_id,
                    'action' => 'CHECKOUT',
                    'created_at' => $now,
                ];
            })->toArray();

            DB::beginTransaction();

            UserActionLog::insert($insert_data);

            $books = tap(Book::whereIn('id', $data['book_ids']), function ($query) {
                $query->update(['status' => 'CHECKED_OUT']);
            })->get();

            DB::commit();

            return $books;
        }catch (\Exception $exception){
            DB::rollBack();

            $book_ids = implode(', ', request('book_ids'));

            Log::error(
                sprintf('Book(s) could not be checked out for %s due to %s', $book_ids, $exception->getMessage()),
                ['stackTrace' => $exception->getTraceAsString()]
            );

            return;
        }
    }
}
