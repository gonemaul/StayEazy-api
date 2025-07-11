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
        Route::get('/{hotel}/room-classes', 'roomsByHotel');
        Route::get('/{hotel}/room-classes/{roomClass}', 'roomDetail');
    });
    Route::get('/cities', 'listCity');
    Route::post('/rooms/availability',  'roomAvailable');
});
Route::post('/midtrans/callback', [\App\Http\Controllers\Api\MidtransController::class, 'handleCallback'])->name('midtrans.callback');


// Auth Route
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Only
    Route::controller(UserController::class)->group(function () {
        Route::prefix('reservations')->group(function () {
            Route::get('/',  'index');
            Route::get('/{id}',  'show');
            Route::post('/create',  'create');
            Route::post('/{reservation}/cancel', 'cancel');
        });
        Route::get('notifications');
    });

    // Staff Only
    Route::middleware('is_staff')->controller(StaffController::class)->prefix('staff')->group(function () {
        Route::prefix('reservations')->group(function () {
            Route::get('/', 'staffReservation');
            Route::post('checkin',  'checkin');
            Route::post('checkout', 'checkout');
        });
    });

    // Admin Only
    Route::middleware('is_admin')->controller(AdminController::class)->prefix('admin')->group(function () {
        Route::prefix('cities')->group(callback: function () {
            Route::get('/', 'cities');
        });
        Route::prefix('hotels')->group(function () {
            Route::post('/', 'storeHotel');
            Route::put('{hotel}/update', 'updateHotel');
        });
        Route::prefix('room-classes')->group(function () {
            Route::post('/create', 'storeRoomClass');
            Route::put('/{roomClass}/update', 'updateRoomClass');
        });
        Route::prefix('room')->group(function () {
            Route::post('/create', 'storeRoom');
            Route::put('/{roomUnit}/update', 'updateRoom');
        });
        Route::prefix('staff')->group(function () {
            Route::post('create', 'createStaff');
            Route::put('{staff}/update', 'updateStaff');
            Route::delete('{staff}/delete', 'deleteStaff');
        });
        Route::post('notifications', 'notifications');
        Route::get('reservations', 'allReservations');
        Route::put('reservations/{reservation}', 'updateReservationStatus');
    });
});
