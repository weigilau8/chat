<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PusherController;

Route::get('/', [PusherController::class, 'index']);
Route::post('/broadcast', [PusherController::class, 'broadcast']);
Route::post('/receive', [PusherController::class, 'receive']);
