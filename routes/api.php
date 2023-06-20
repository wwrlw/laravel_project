<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\CommentController;

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

Route::get('/auth/registr', [AuthController::class, 'create']);
Route::post('/auth/registr', [AuthController::class, 'store']);
Route::get('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/login', [AuthController::class, 'customLogin']);
Route::get('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//Article
Route::get('/', [ArticleController::class, 'index']);

Route::group(['prefix'=>'/article', 'middleware'=>'auth:sanctum'], function(){
    Route::get('/create', [ArticleController::class, 'create']);
    Route::post('/store', [ArticleController::class, 'store']);
    Route::get('/show/{id}', [ArticleController::class, 'show'])->name('show')->middleware('path');
    Route::get('/{id}/edit', [ArticleController::class, 'edit']);
    Route::put('/{id}', [ArticleController::class, 'update']);
    Route::get('/{id}/delete', [ArticleController::class, 'destroy']);
});

//Comment
Route::resource('comment', CommentController::class)->middleware('auth:sanctum');
Route::get('/comment/{comment}/accept', [CommentController::class, 'accept']);
Route::get('/comment/{comment}/reject', [CommentController::class, 'reject']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });