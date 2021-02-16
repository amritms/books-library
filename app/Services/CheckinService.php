<?php

namespace App\Services;


use App\Models\Book;
use App\Models\UserActionLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckinService
{
    /**
     * @param $data
     * @return Illuminate\Database\Eloquent\Collection|null
     */
    public function __invoke($data)
    {
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

            return $books;
        } catch (\Exception $exception) {
            DB::rollBack();

            Log::error(
                sprintf('Book(s) could not be checked in for %s due to %s', request('book_id'), $exception->getMessage()),
                ['stackTrace' => $exception->getTraceAsString()]
            );

            return;
        }
    }
}
