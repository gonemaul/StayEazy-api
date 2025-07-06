<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ReservationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/rooms/{id}', [RoomController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);

    Route::middleware('is_admin')->prefix('admin')->group(function () {
        Route::get('/cities', [AdminController::class, 'cities']);
        Route::post('/hotels', [AdminController::class, 'storeHotel']);
        Route::post('/room-classes', [AdminController::class, 'storeRoomClass']);
        Route::post('/rooms', [AdminController::class, 'storeRoom']);

        Route::get('/reservations', [AdminController::class, 'allReservations']);
        Route::put('/reservations/{id}', [AdminController::class, 'updateReservationStatus']);
    });
});
