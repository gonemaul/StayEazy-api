<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PublicController;

// Public Route
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::controller(PublicController::class)->group(function () {
    Route::prefix('hotels')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}/rooms', 'rooms');
        Route::get('/{id}/rooms/{rid}', 'roomDetail');
    });

    Route::get('/rooms',  'index');
    Route::post('/rooms/availability',  'index');
    Route::get('/rooms/{id}',  'show');
});

// Auth Route
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Only
    Route::controller(UserController::class)->group(function () {
        Route::prefix('reservations')->group(function () {
            Route::get('/',  'index');
            Route::get('/{id}',  'show');
            Route::post('/',  'store');
            Route::post('/{id}/cancel');
        });
        Route::get('notifications');
    });

    // Staff Only
    Route::middleware('is_staff')->controller(StaffController::class)->prefix('staff')->group(function () {
        Route::get('reservations');
        Route::post('checkin',  'start');
        Route::post('checkin/verify',  'confirm');
        Route::post('checkout');
    });

    // Admin Only
    Route::middleware('is_admin')->controller(AdminController::class)->prefix('admin')->group(function () {
        Route::prefix('cities')->group(callback: function () {
            Route::get('/', 'cities');
        });
        Route::prefix('hotels')->group(function () {
            Route::post('/', 'storeHotel');
            Route::post('{id}/update', 'updateHotel');
        });
        Route::prefix('rooms')->group(function () {
            Route::post('room-classes', 'storeRoomClass');
            Route::put('room-classes');
            Route::post('rooms', 'storeRoom');
            Route::put('rooms');
        });
        Route::prefix('staff')->group(function () {
            Route::post('create');
            Route::put('{id}/update');
            Route::delete('{id}/delete');
        });
        Route::post('notifications');
        Route::post('room-prices');
        Route::get('reservations', 'allReservations');
        Route::put('reservations/{id}', 'updateReservationStatus');
    });
});
