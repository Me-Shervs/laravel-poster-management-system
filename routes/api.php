<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\PosterScheduleController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes (NO LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (SANCTUM REQUIRED)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {

        Route::apiResource('posters', PosterController::class);

        Route::post(
            'posters/{poster}/schedule',
            [PosterScheduleController::class, 'store']
        );
    });