<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PushTokenController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\OrganizerController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\EmailVerificationController;

Route::prefix('v1')->group(function () {
    Route::get('/ping', fn () => response()->json(['message' => 'ok']))->name('api.ping');

    // Public endpoints (guest access)
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{event}', [EventController::class, 'show']);
    Route::get('/events/suggest', [EventController::class, 'suggest']);
    Route::get('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
    Route::get('/departments', [\App\Http\Controllers\Api\DepartmentController::class, 'index']);
    Route::get('/media', [\App\Http\Controllers\Api\MediaController::class, 'index']);

    // Auth
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/forgot-password', [PasswordResetController::class, 'forgot']);
    Route::post('/auth/reset-password', [PasswordResetController::class, 'reset']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/email/verification-notification', [EmailVerificationController::class, 'send']);
        Route::get('/auth/verify-email', [EmailVerificationController::class, 'verify'])->name('verification.verify');

        // Events (auth required actions only)

        // Registrations
        Route::get('/me/registrations', [RegistrationController::class, 'myRegistrations']);
        Route::post('/events/{event}/register', [RegistrationController::class, 'register']);
        Route::delete('/events/{event}/register', [RegistrationController::class, 'unregister']);
        Route::get('/events/{event}/registrants', [OrganizerController::class, 'registrants'])->middleware('role:super_admin|staff_admin|staff_organizer');

        // Certificates
        Route::get('/me/certificates', [CertificateController::class, 'mine']);
        Route::post('/certificates/issue', [CertificateController::class, 'issue'])->middleware('role:super_admin|staff_admin|staff_organizer');

        // Feedback
        Route::post('/events/{event}/feedback', [FeedbackController::class, 'store']);

        // Media
        Route::get('/media', [MediaController::class, 'index']);
        Route::post('/media/{media}/favorite', [MediaController::class, 'toggleFavorite']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead']);

        // Push tokens (FCM / OneSignal)
        Route::post('/push-tokens', [PushTokenController::class, 'upsert']);
        Route::post('/push-tokens/revoke', [PushTokenController::class, 'revoke']);

        // Attendance
        Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->middleware('role:super_admin|staff_admin|staff_organizer');

        // Profile
        Route::get('/profile', [ProfileController::class, 'me']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::post('/profile/password', [ProfileController::class, 'changePassword']);
    });
});


