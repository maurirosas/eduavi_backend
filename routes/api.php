<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\OccupationalTherapyFormController;
use App\Http\Controllers\PsychopedagogicFormController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SpeechLanguageFormController;
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

    // --- ADMIN ---
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('appointments', [AppointmentController::class, 'index']);
        Route::get('appointments/{id}', [AppointmentController::class, 'show']);
        Route::delete('appointments/{id}', [AppointmentController::class, 'destroy']);
    });

    // --- PROFESSIONAL ---
    Route::middleware([IsProfessional::class])->group(function () {
        // Services
        Route::post('services', [ServiceController::class, 'store']);
        Route::put('services/{id}', [ServiceController::class, 'update']);
        Route::delete('services/{id}', [ServiceController::class, 'destroy']);

        // Appointments
        Route::get('appointments', [AppointmentController::class, 'index']);
        Route::get('appointments/{id}', [AppointmentController::class, 'show']);
        Route::put('appointments/{id}', [AppointmentController::class, 'update']);

        // Kardex (solo profesional)
        Route::get('kardex', [KardexController::class, 'index']);
        Route::get('kardex/{id}', [KardexController::class, 'show']);
        Route::post('kardex', [KardexController::class, 'store']);
        Route::put('kardex/{id}', [KardexController::class, 'update']);

        //Ficha psicopedagógica
        Route::get('psychopedagogic-forms', [PsychopedagogicFormController::class, 'index']);
        Route::get('psychopedagogic-forms/{id}', [PsychopedagogicFormController::class, 'show']);
        Route::post('psychopedagogic-forms', [PsychopedagogicFormController::class, 'store']);
        Route::put('psychopedagogic-forms/{id}', [PsychopedagogicFormController::class, 'update']);
        Route::delete('psychopedagogic-forms/{id}', [PsychopedagogicFormController::class, 'destroy']);

        //Ficha terapia ocupacional
        Route::get('occupational-therapy-forms', [OccupationalTherapyFormController::class, 'index']);
        Route::get('occupational-therapy-forms/{id}', [OccupationalTherapyFormController::class, 'show']);
        Route::post('occupational-therapy-forms', [OccupationalTherapyFormController::class, 'store']);
        Route::put('occupational-therapy-forms/{id}', [OccupationalTherapyFormController::class, 'update']);
        Route::delete('occupational-therapy-forms/{id}', [OccupationalTherapyFormController::class, 'destroy']);

        Route::get('speech-language-forms', [SpeechLanguageFormController::class, 'index']);
        Route::get('speech-language-forms/{id}', [SpeechLanguageFormController::class, 'show']);
        Route::post('speech-language-forms', [SpeechLanguageFormController::class, 'store']);
        Route::put('speech-language-forms/{id}', [SpeechLanguageFormController::class, 'update']);
        Route::delete('speech-language-forms/{id}', [SpeechLanguageFormController::class, 'destroy']);
    });

    // --- CLIENT ---
    Route::middleware([IsClient::class])->group(function () {
        Route::get('appointments', [AppointmentController::class, 'index']);
        Route::get('appointments/{id}', [AppointmentController::class, 'show']);
        Route::post('appointments', [AppointmentController::class, 'store']);
        Route::put('appointments/{id}', [AppointmentController::class, 'update']);
        Route::delete('appointments/{id}', [AppointmentController::class, 'destroy']);
    });
});

