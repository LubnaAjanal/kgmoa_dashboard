<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('signin');
});

Route::get('/register-page', function () {
    return view('registeredUsers');
});

// Route::apiResource('registers', RegisterController::class);
// Route::resource('register', RegisterController::class);

