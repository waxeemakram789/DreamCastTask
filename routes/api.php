<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index'])->middleware('forcejson');
Route::post('/users', [UserController::class, 'store'])->middleware('forcejson');