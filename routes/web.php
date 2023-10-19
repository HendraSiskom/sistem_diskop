<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ListData\ListBarangController;
use App\Http\Controllers\Utility\PenggunaController;
use App\Http\Controllers\Utility\PeranController;
use App\Http\Controllers\Utility\ProfilController;
use App\Http\Controllers\MasterData\KdWilayahController;
use App\Http\Controllers\MasterData\WilayahController;
use App\Http\Controllers\ReportingOutputController;
use App\Http\Controllers\Transaksi\TransaksiController;



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
        Route::post('pengguna/update-status', [PenggunaController::class, 'updateStatus'])->name('pengguna.update_status');

        // peran
        Route::resource('peran', PeranController::class);
        Route::post('peran/load_data', [PeranController::class, 'load_data'])->name('peran.load_data');

        //profil
        Route::resource('profil', ProfilController::class);
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

    // reporting output
    Route::resource('reporting_output', ReportingOutputController::class);

    // Lis barang
    Route::group(['prefix' => 'list_data'], function () {
        Route::resource('listbarang', ListBarangController::class);
        Route::post('listbarang/listbarang', [ListBarangController::class, 'list_barang'])->name('listbarang.list_barang');
        Route::post('listbarang/simpanbarang', [ListBarangController::class, 'simpanbarang'])->name('listbarang.simpanbarang');
        Route::post('listbarang/editbarang', [ListBarangController::class, 'editbarang'])->name('listbarang.editbarang');
        Route::post('listbarang/hapusbarang', [ListBarangController::class, 'hapusbarang'])->name('listbarang.hapusbarang');
    });

    // Transaksi Barang
    Route::group(['prefix' => 'transaksi_data'], function () {
        Route::resource('transaksi', TransaksiController::class);
        Route::post('transaksi/listbarang', [TransaksiController::class, 'listbarang'])->name('transaksi.listbarang');
        Route::post('transaksi/simpanbarang', [TransaksiController::class, 'simpanbarang'])->name('transaksi.simpanbarang');
        Route::post('transaksi/wherelistbarang', [TransaksiController::class, 'wherelistbarang'])->name('transaksi.wherelistbarang');
        Route::post('transaksi/updatebarang', [TransaksiController::class, 'updatebarang'])->name('transaksi.updatebarang');
        Route::post('transaksi/hapusbarang', [TransaksiController::class, 'hapusbarang'])->name('transaksi.hapusbarang');
    });
});
