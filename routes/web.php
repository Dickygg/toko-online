<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\produkController;
use App\Http\Controllers\UserController;
use App\Models\kategori;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('backend.login');
});
// Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])->name('backend.beranda');
Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login');
Route::post('backend/login', [LoginController::class, 'authbackend'])->name('backend.login');
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');

Route::middleware(['auth'])->group(function () {
    Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])
        ->name('backend.beranda');
    Route::resource('backend/user', UserController::class)->names('backend.user');
    Route::resource('backend/kategori', KategoriController::class)->names('backend.kategori');
    Route::resource('backend/produk', produkController::class)->names('backend.produk');
    //route untuk menyimpan foto tambahan
    Route::post('foto-produk/store', [ProdukController::class, 'storeFoto'])->name('backend.foto_produk.store')->middleware('auth');
    // Route untuk menghapus foto
    Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])->name('backend.foto_produk.destroy')->middleware('auth');

    Route::get('backend/laporan/formuser', [UserController::class, 'fromUser'])->name('backend.laporan.formuser');
    Route::post('backend/laporan/cetakuser', [UserController::class, 'cetaklaporanUser'])->name('backend.laporan.cetakuser');
    Route::get('backend/laporan/formkategori', [KategoriController::class, 'formkategori'])->name('backend.laporan.formkategori');
    Route::post('backend/laporan/cetakkategori', [KategoriController::class, 'cetaklaporankategori'])->name('backend.laporan.cetakkategori');
});
