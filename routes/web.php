<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Utility\PenggunaController;
use App\Http\Controllers\MasterData\KdWilayahController;
use App\Http\Controllers\MasterData\WilayahController;

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

Route::get('', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'authenticate'])->name('login.index')->middleware(['throttle:3,1']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth', 'auth.session'], function () {
    // Route::get('beranda', [HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => 'utility'], function () {
        // pengguna
        Route::resource('pengguna', PenggunaController::class);
        Route::post('pengguna/load_data', [PenggunaController::class, 'load_data'])->name('pengguna.load_data');
    });

    // master data
    Route::group(['prefix' => 'master_data'], function () {
        // wilayah
        Route::resource('kd_wilayah', KdWilayahController::class)->except('show');
        Route::post('kd_wilayah/load_data', [KdWilayahController::class, 'show'])->name('kd_wilayah.load_data');

        // wilayah
        Route::resource('wilayah', WilayahController::class)->except('show');
        Route::post('wilayah/load_data', [WilayahController::class, 'show'])->name('wilayah.load_data');
    });


});
