<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckinRequest;
use App\Http\Resources\BookResource;
use App\Services\CheckinService;
use Illuminate\Http\Response;

class CheckinController extends Controller
{
    public function __invoke(CheckinRequest $request, CheckinService $checkinService)
    {
        $books = $checkinService($request->validated());

        if(! $books) {
            return response()->json([
                'message' => 'Book(s) could not be checked in.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Book(s) Checked in Successfully.',
            'data' => BookResource::collection($books)
        ], Response::HTTP_OK);

    }
}
