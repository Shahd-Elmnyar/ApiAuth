<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

        //auth
//register
Route::post('/register',[AuthController::class,'register']) ;
//logout
Route::get('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
//login
Route::post('/login',[AuthController::class,'login']);
