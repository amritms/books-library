<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\BookResource;
use App\Services\CheckoutService;
use Illuminate\Http\Response;

class CheckoutController extends Controller
{
    /**
     * @param CheckoutRequest $request
     * @param CheckoutService $checkoutService
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CheckoutRequest $request, CheckoutService $checkoutService)
    {
        $books = $checkoutService($request->validated());

        if(! $books) {
            return response()->json([
                'message' => 'Book(s) could not be checked out.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Book(s) Checked out Successfully.',
            'data' => BookResource::collection($books)
        ], Response::HTTP_OK);
    }
}
