<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', \App\Http\Controllers\Auth\RegisterController::class);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function($route) {
    $route->post('/logout', [AuthController::class, 'logout']);

    $route->post('/books', [BooksController::class, 'store']);
    $route->get('/books', [BooksController::class, 'index']);

    $route->post('/checkout', CheckoutController::class);
    $route->post('/checkin', CheckinController::class);
});
