<?php


use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/index', [UserController::class, 'index']);
Route::get('/', function () {
    // Logika untuk menyimpan produk baru
    return view('index');
});
Route::get('/admin', function () {
    // Logika untuk menyimpan produk baru
    return view('admin.admin-dashboard');
});
Route::get('/user', function () {
    // Logika untuk menyimpan produk baru
    return view('user.user-dashboard');
});
Route::get('/login', function () {
    // Logika untuk menyimpan produk baru
    return view('login');
});
Route::get('/booking', function () {
    // Logika untuk menyimpan produk baru
    return view('booking');
});