<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login.page');
    Route::post('/login/process', [AuthController::class, 'loginProcess'])->name('login.process');
    Route::get('/register', [AuthController::class, 'registerPage'])->name('register.page');
    Route::post('/register/process', [AuthController::class, 'registerProcess'])->name('register.process');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::prefix('dashboard')->group(function () {
    Route::get('/user', [DashboardController::class, 'userDashboard'])->name('dashboard.users');
    Route::get('/admin', [DashboardController::class, 'adminDashboard'])->name('dashboard.admin');
});
