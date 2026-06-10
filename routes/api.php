<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth;

Route::post('/register', [Auth::class, 'register']);
Route::post('/login', [Auth::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [Auth::class, 'user']);
    Route::post('/logout', [Auth::class, 'logout']);
});
