<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\NotificationController;

Route::prefix('v1')->group(function () {
    Route::get('/ping', fn () => response()->json(['message' => 'ok']))->name('api.ping');

    // Auth
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Events
        Route::get('/events', [EventController::class, 'index']);
        Route::get('/events/{event}', [EventController::class, 'show']);

        // Registrations
        Route::get('/me/registrations', [RegistrationController::class, 'myRegistrations']);
        Route::post('/events/{event}/register', [RegistrationController::class, 'register']);
        Route::delete('/events/{event}/register', [RegistrationController::class, 'unregister']);

        // Certificates
        Route::get('/me/certificates', [CertificateController::class, 'mine']);

        // Feedback
        Route::post('/events/{event}/feedback', [FeedbackController::class, 'store']);

        // Media
        Route::get('/media', [MediaController::class, 'index']);
        Route::post('/media/{media}/favorite', [MediaController::class, 'toggleFavorite']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
    });
});


