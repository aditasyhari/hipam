<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeluhanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\NotifikasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('index.login');
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function() {
    Route::get('/home', [HomeController::class, 'home']);
    Route::get('/profile/edit-profile', [UserController::class. 'editProfile']);
    Route::get('/profile/ganti-password', [UserController::class. 'gantiPassword']);
    
    Route::get('/keluhan', [KeluhanController::class. 'index']);
    Route::get('/pembayaran', [PembayaranController::class. 'index']);
    Route::get('/notifikasi', [NotifikasiController::class. 'index']);

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

});
