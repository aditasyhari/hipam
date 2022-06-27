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

    // profile
    Route::get('/profile/edit-profile', [UserController::class, 'editProfile']);
    Route::put('/profile/edit-profile', [UserController::class, 'updateProfile']);
    Route::get('/profile/ganti-password', [UserController::class, 'gantiPassword']);
    Route::put('/profile/ganti-password', [UserController::class, 'updatePassword']);
    
    // keluhan
    Route::get('/keluhan', [KeluhanController::class, 'index']);
    Route::post('/keluhan/list', [KeluhanController::class, 'keluhanList']);
    Route::post('/keluhan/tambah', [KeluhanController::class, 'tambah']);
    Route::put('/keluhan/update/{id}', [KeluhanController::class, 'update']);
    Route::delete('/keluhan/delete/{id}', [KeluhanController::class, 'delete']);

    Route::get('/pembayaran', [PembayaranController::class, 'index']);
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['petugas'])->group(function() {
        // user
        Route::get('/user', [UserController::class, 'user']);
        Route::post('/user/list', [UserController::class, 'userList']);
        Route::post('/user/tambah', [UserController::class, 'tambahUser']);
        Route::put('/user/update/{id}', [UserController::class, 'updateUser']);
        Route::delete('/user/delete/{id}', [UserController::class, 'deleteUser']);
    });

});
