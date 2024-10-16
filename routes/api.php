<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CodeController;
use App\Http\Controllers\Api\PasswordResetController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class) -> group(function () {
    Route::post('/auth/register', 'register');
    Route::post('/auth/login', 'login');
    Route::get('/auth/user', 'getUserDetails');
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
    Route::get('/code/{id}', 'show');
    Route::put('/code/{id}', 'update');
    Route::delete('/code/{id}', 'destroy');
});