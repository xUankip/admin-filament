<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::view('/dashboard/student', 'pages.student-dashboard')->name('student.dashboard');
