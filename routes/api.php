<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PublicationController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AblyController;

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

Route::get('ably/auth', [AblyController::class, 'auth']);
Route::post('ably/send', [AblyController::class, 'sendMessage']);
//ruta para realizar un login 
Route::post('/users/login', [UserController::class, 'login']);
Route::get('/users/login', [UserController::class, 'login']);

Route::get('/profiles/user/{userId}', [ProfileController::class, 'getProfileByUserId']);


