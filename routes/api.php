<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::middleware(['auth:api'])->group(function(){

    // profile
    Route::get('profile',[ProfileController::class,'profile']);
    Route::get('profile-posts',[ProfileController::class,'profilePost']);

    // logout
    Route::post('logout',[AuthController::class,'logout']);

    // Category
    Route::post('category',[CategoryController::class,'category']);

    // post 
    Route::get('post',[PostController::class,'index']);
    Route::post('post',[PostController::class,'create']);
    Route::get('post/details/{id}',[PostController::class,'details']);
});
