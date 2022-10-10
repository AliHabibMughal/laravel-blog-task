<?php

use App\Http\Controllers\{AuthController, PostController};
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

Route::post('registeruser', [AuthController::class, 'createUser']);
Route::post('loginuser', [AuthController::class, 'loginUser']);
Route::apiResource('posts', PostController::class)->middleware('auth:sanctum')