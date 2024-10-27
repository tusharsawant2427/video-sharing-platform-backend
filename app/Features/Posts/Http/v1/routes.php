<?php

use App\Features\Posts\Http\v1\Controllers\UserPostController;
use App\Features\Posts\Http\v1\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/my-posts', [UserPostController::class, 'index']);
Route::post('my-posts/create', [UserPostController::class, 'store']);
Route::get('my-posts/{post:identifier}/edit', [UserPostController::class, 'edit']);
Route::put('my-posts/{post:identifier}/update', [UserPostController::class, 'update']);
Route::post('my-posts/{post:identifier}/upload-video', [UserPostController::class, 'uploadVideo']);
Route::delete('my-posts/{post:identifier}', [UserPostController::class, 'delete']);

Route::get('/', [PostController::class, 'index']);
Route::post('{post:identifier}/like', [PostController::class, 'likePost']);
Route::post('{post:identifier}/comment', [PostController::class, 'commentPost']);
Route::post('comment/{postComment:identifier}/like', [PostController::class, 'likeComment']);
