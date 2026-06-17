<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pemilik\DashboardPemilikController;
use App\Http\Controllers\Pemilik\LaporanKeuanganController;
use App\Http\Controllers\Pemilik\LaporanServisController;
use App\Http\Controllers\Pemilik\MonitorStokController;
use App\Http\Controllers\Pemilik\MonitorOrderController;
use App\Http\Controllers\Pemilik\ManajemenAkunController;
use App\Http\Controllers\Pemilik\PengaturanBengkelController;

Route::middleware(['auth', 'pemilik'])->prefix('pemilik')->group(function () {
    Route::get('/dashboard', [DashboardPemilikController::class, 'index'])->name('pemilik.dashboard');
    Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('pemilik.laporan-keuangan');
    Route::get('/laporan-servis', [LaporanServisController::class, 'index'])->name('pemilik.laporan-servis');
    Route::get('/monitor-stok', [MonitorStokController::class, 'index'])->name('pemilik.monitor-stok');
    Route::get('/monitor-order', [MonitorOrderController::class, 'index'])->name('pemilik.monitor-order');
    Route::get('/monitor-order/{id}', [MonitorOrderController::class, 'show'])->name('pemilik.monitor-order.show');
    Route::get('/manajemen-akun', [ManajemenAkunController::class, 'index'])->name('pemilik.manajemen-akun');
    Route::post('/manajemen-akun', [ManajemenAkunController::class, 'store'])->name('pemilik.manajemen-akun.store');
    Route::patch('/manajemen-akun/{user}/toggle', [ManajemenAkunController::class, 'toggleStatus'])->name('pemilik.manajemen-akun.toggle');
    Route::patch('/manajemen-akun/{user}/reset-password', [ManajemenAkunController::class, 'resetPassword'])->name('pemilik.manajemen-akun.reset-password');
    Route::get('/pengaturan', [PengaturanBengkelController::class, 'index'])->name('pemilik.pengaturan');
    Route::put('/pengaturan', [PengaturanBengkelController::class, 'update'])->name('pemilik.pengaturan.update');
});
