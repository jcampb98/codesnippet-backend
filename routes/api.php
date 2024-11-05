<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CodeController;
use App\Http\Controllers\Api\PasswordResetController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class) -> group(function () {
    Route::post('/auth/register', 'register');
    Route::post('/auth/login', 'login');
    Route::get('/auth/user', 'getUserDetails');
    Route::patch('/auth/update/{userId}', 'updateUser');
    Route::delete('/auth/delete/{userId}', 'deleteUser');
    Route::get('/validate-token', 'validateToken');
    Route::post('/logout', 'login');
    Route::post('/auth/refresh', 'login');
});

Route::controller(PasswordResetController::class) -> group(function () {
    Route::post('forgot-password', 'sendResetLinkEmail');
    Route::post('reset-password', 'reset');
});

Route::controller(CodeController::class) -> group(function () {
    Route::post('/code/create', 'create');
    Route::get('/code/{id}', 'showAll');
    Route::get('/code/guid/{guid}', 'showByGuid');
    Route::put('/code/{id}', 'update');
    Route::delete('/code/{id}', 'destroy');
});