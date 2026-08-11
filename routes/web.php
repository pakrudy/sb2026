<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\TrackingController;
use App\Livewire\CabangHk;
use App\Livewire\DaftarBarang;
use App\Livewire\InputBarang;
use App\Livewire\KelolaCabang;
use App\Livewire\SortingPusat;
use Illuminate\Support\Facades\Route;

// Public Tracking Route (Default Home)
Route::get('/', [TrackingController::class, 'index'])->name('tracking');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Staff & Admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/input-barang', InputBarang::class)->name('input-barang');
    Route::get('/daftar-barang', DaftarBarang::class)->name('daftar-barang');
    Route::get('/sorting-pusat', SortingPusat::class)->name('sorting-pusat');
    Route::get('/cabang-hk', CabangHk::class)->name('cabang-hk');
    Route::get('/kelola-cabang', KelolaCabang::class)->name('kelola-cabang');

    Route::get('/barang/{id}/print', [PrintController::class, 'resi'])->name('print.resi');
});
