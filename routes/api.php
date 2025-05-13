<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!`
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::get('/search', [\App\Http\Controllers\Api\GiphyController::class, 'search']);
    Route::get('/search-by-id/{id}', [\App\Http\Controllers\Api\GiphyController::class, 'searchById']);
    Route::post('/save-favorite', [\App\Http\Controllers\Api\GiphyController::class, 'saveFavorite']);
});