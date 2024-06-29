<?php

use App\Http\Controllers\Api\AnggotaKeluargaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->middleware('api');
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');



Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('bookings', [BookingController::class, 'index']);
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings/{id}', [BookingController::class, 'show']);
    Route::put('bookings/{id}', [BookingController::class, 'update']);
    Route::delete('bookings/{id}', [BookingController::class, 'destroy']);

    
    Route::get('anggota', [AnggotaKeluargaController::class, 'index']);
    Route::post('anggota', [AnggotaKeluargaController::class, 'store']);
    Route::get('anggota/{id}', [AnggotaKeluargaController::class, 'show']);
    Route::put('anggota/{id}', [AnggotaKeluargaController::class, 'update']);
    Route::delete('anggota/{id}', [AnggotaKeluargaController::class, 'destroy']);
});    



