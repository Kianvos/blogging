<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\UserController;
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


//Get all stories
Route::get('/stories', [StoryController::class, 'getAllStories']);
Route::get('/story/{id}', [StoryController::class, 'getUserStory']);


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //Stories user
    Route::get('/stories/user', [UserController::class, 'getUserStories']);
    Route::post('/story/create/', [StoryController::class, 'createStory']);
    Route::put('/story/edit/{id}', [StoryController::class, 'editStory']);
    Route::delete('/story/delete/{id}', [StoryController::class, 'deleteStory']);

    //Posts
    Route::post('/post/{id}', [PostController::class, 'getPost']);
    Route::post('/post/create/{storyId}', [PostController::class, 'createPost']);
    Route::put('/post/edit/{id}', [PostController::class, 'editPost']);
    Route::delete('/post/delete/{id}', [PostController::class, 'deletePost']);
});


