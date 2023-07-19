<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BooksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::delete('/user/logout', [AuthController::class, 'logout']);

Route::get('/books', [BooksController::class, 'getBook']);
Route::post('/books/add', [BooksController::class, 'add']);
Route::put('/books/{book_id}/edit', [BooksController::class, 'edit']);
Route::get('/books/{book_id}', [BooksController::class, 'getBookById']);
Route::delete('/books/{book_id}', [BooksController::class, 'delete']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
