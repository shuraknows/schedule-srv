<?php

use App\UserInterface\Http\ScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/schedule', [ScheduleController::class, 'create']);

Route::put('/schedule/{scheduleId}/euribor-rate', [ScheduleController::class, 'updateEuriborRate']);
