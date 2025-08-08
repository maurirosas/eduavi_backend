<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServicioController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsClient;
use App\Http\Middleware\IsProfessional;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Rutas privadas
Route::middleware([IsUserAuth::class])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'getUser']);

    // Solo ADMIN
    Route::middleware([IsAdmin::class])->group(function () {
        // Rutas exclusivas para admin
    });

    // Solo PROFESSIONAL
    Route::middleware([IsProfessional::class])->group(function () {
        // Rutas exclusivas para profesionales
    });

    // Solo CLIENT
    Route::middleware([IsClient::class])->group(function () {
        // Rutas exclusivas para clientes
    });
});


