<?php

use Illuminate\Support\Facades\Route;
use Wiz\Helper\Controllers\ImageController;

Route::group(['prefix' => 'storage'], function () {
    Route::get('/thumb/{file}', [ImageController::class, 'thumb'])
        ->where('file', '(.*)')
        ->name("zi_thumb");
});

Route::group(['prefix' => 'storage'], function () {
    Route::get('/medium/{file}', [ImageController::class, 'medium'])
        ->where('file', '(.*)')
        ->name("zi_medium");
});

Route::group(['prefix' => 'storage'], function () {
    Route::get('/{file}', [ImageController::class, 'full'])
        ->where('file', '(.*)')
        ->name("zi_link.thumb");
});

Route::group(['prefix' => 'storage'], function () {
    Route::get('/file/{file}', [ImageController::class, 'thumb'])
        ->where('file', '(.*)')
        ->name("zi_link.file");
});
