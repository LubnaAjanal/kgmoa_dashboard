<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['middleware' => ['web']], function () {

    Route::controller(ViewsController::class)->group(function () {
        Route::get('/dashboard', 'index');
        Route::get('/registered-users', 'register');
        Route::get('/user-attendance', 'userAttendance');
        Route::get('/settings', 'settings');
    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout');
    });

    Route::apiResource('registers', RegisterController::class);
    Route::apiResource('attendance', AttendanceController::class);
Route::resource('register', RegisterController::class);

});