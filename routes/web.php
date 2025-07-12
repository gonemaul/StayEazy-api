<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::prefix('docs')->name('docs.')->group(function () {
    Route::get('/', function () {
        return view('docs.documentation');
    })->name('overview');
    Route::get('/public', function () {
        return view('docs.public');
    })->name('public');
    Route::get('/user', function () {
        return view('docs.user');
    })->name('user');
    Route::get('/staff', function () {
        return view('docs.staff');
    })->name('staff');
    Route::get('/admin', function () {
        return view('docs.admin');
    })->name('admin');
    Route::get('/auth', function () {
        return view('docs.auth');
    })->name('auth');
});