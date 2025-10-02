<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PublicationController;
use App\Models\Category;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AblyTestController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('users', UserController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('profiles', ProfileController::class);
Route::apiResource('messages', MessageController::class);
Route::apiResource('publications', PublicationController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('notifications', NotificationController::class);


