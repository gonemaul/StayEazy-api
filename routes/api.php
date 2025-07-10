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
        Route::get('/', 'listHotel');
        Route::get('/{id}/rooms', 'roomsByHotel');
        Route::get('/{id}/rooms/{rid}', 'roomDetail');
    });

    Route::post('/rooms/availability',  'roomAvailable');
});

// Auth Route
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Only
    Route::controller(UserController::class)->group(function () {
        Route::prefix('reservations')->group(function () {
            // daftar my reservasi
            Route::get('/',  'index');
            // detail reservasi + status
            Route::get('/{id}',  'show');
            // buat reservasi
            Route::post('/create',  'create');
            // cancel reservasi
            Route::post('/{id}/cancel');
        });
        // get notifikasi
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
            Route::put('{id}/update', 'updateHotel');
        });
        Route::prefix('rooms')->group(function () {
            Route::post('room-classes', 'storeRoomClass');
            Route::put('room-classes', 'updateRoomClass');
            Route::post('/create', 'storeRoom');
            Route::put('/{$id}/update', 'updateRoom');
        });
        Route::prefix('staff')->group(function () {
            Route::post('create', 'createStaff');
            Route::put('{id}/update', 'updateStaff');
            Route::delete('{id}/delete', 'deleteStaff');
        });
        Route::post('notifications', 'notifications');
        Route::get('reservations', 'allReservations');
        Route::put('reservations/{id}', 'updateReservationStatus');
    });
});
