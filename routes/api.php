<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmailCheckController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'show']);

Route::post('/email-check', [EmailCheckController::class, 'check']);

Route::get('/doctors', [DoctorController::class, 'index']);
