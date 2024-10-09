<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    //Route::apiResource('alumnos', AlumnoController::class);
    //Route::get('alumnos', [AlumnoController::class, 'index']);
});
Route::middleware('api')->group(function () {
    Route::get('alumnos', [AlumnoController::class, 'index']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});