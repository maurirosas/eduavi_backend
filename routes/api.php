<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServicioController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Support\Facades\Route;

//PUBLIC ROUTES
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//PRIVATE ROUTES
Route::middleware([IsUserAuth::class])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'getUser');
    });

    Route::get('servicios', [ServicioController::class, 'getServices']);

    Route::middleware([IsAdmin::class])->group(function () {
        Route::controller(ServicioController::class)->group(function () {
            Route::post('servicios', 'addService');
            Route::get('/servicios/{id}', 'getServiceById');
            Route::patch('/servicios/{id}', 'updateServiceById');
            Route::delete('/servicios/{id}', 'deleteServiceById');
        });
    });
});


