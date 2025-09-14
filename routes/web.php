<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\EmailVerificationController;

Route::view('/dashboard/student', 'pages.student-dashboard')->name('student.dashboard');

// Email verification (signed URL, stateless for mobile)
Route::middleware(['signed', 'throttle:6,1'])->group(function () {
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyPublic'])
        ->name('verification.verify');
});
