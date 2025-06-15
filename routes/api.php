<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommmentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// get all posts    
Route::get('/all/post', [PostController::class, 'getAllPost']);
// get single post    
Route::get('/getPost/{post_id}', [PostController::class, 'getSinglePost']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // blog api endpoint start  
    Route::post('/add/post', [PostController::class, 'addnewpost']);
    Route::put('/edit/post', [PostController::class, 'editPost']);
    //aproach 2  
    Route::put('/edit/post/{post_id}', [PostController::class, 'editPost2']);
    //delete post
    Route::delete('/delete/post/{post_id}', [PostController::class, 'deletePost']);
    //post commment
    Route::post('/comment', [CommmentController::class, 'postComment']);
    Route::post('/like', [LikeController::class, 'likePost']);
});
