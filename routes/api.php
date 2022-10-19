<?php

use App\Http\Controllers\{
    AuthController,
    PostController,
    CommentController,
    CategoryController,
    ImageController,
    LikeController,
};
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
Route::middleware('auth:sanctum')->group(function () {

    Route::get('users', [AuthController::class, 'index']);
    Route::get('user/{id}', [AuthController::class, 'show']);
    Route::delete('user/{id}', [AuthController::class, 'destroy']);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('comments', CommentController::class);
    Route::post('reply', [CommentController::class, 'replyStore']);
    Route::delete('reply/{id}', [CommentController::class, 'destroyReply']);
    Route::patch('reply/{id}', [CommentController::class, 'updateReply']);
    Route::apiResource('postlikes', LikeController::class);
    
});
Route::apiResource('categories', CategoryController::class);
Route::apiResource('images', ImageController::class);
