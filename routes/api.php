<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\PosterScheduleController;

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {

        Route::apiResource('posters', PosterController::class);

        Route::post(
            'posters/{poster}/schedule',
            [PosterScheduleController::class, 'store']
        );
    });