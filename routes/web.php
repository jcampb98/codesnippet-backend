<?php

use Illuminate\Support\Facades\Route;

Route::get('reset-password/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->name('password.reset');
