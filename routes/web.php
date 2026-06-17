<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\JenisServisController;
use App\Http\Controllers\SukuCadangController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\OrderServisController;
use App\Http\Controllers\DetailSukuCadangController;
use App\Http\Controllers\NotaPembayaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiServisController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (auth()->check())
    {

        if (auth()->user()->role == 'admin')
        {
            return redirect('/dashboard-admin');
        }

        if (auth()->user()->role == 'pemilik')
        {
            return redirect('/pemilik/dashboard');
        }

    }

    return redirect('/login');

});


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin'
])->group(function () {

    Route::resource(
        'pelanggan',
        PelangganController::class
    )->except(['create', 'store']);
    
    Route::get(
        '/dashboard-admin',
        [DashboardController::class, 'admin']
    );

    Route::resource(
        'kendaraan',
        KendaraanController::class
    )->except(['create', 'store']);

    Route::resource(
        'jenis-servis',
        JenisServisController::class
    );

    Route::resource(
        'kategori-servis',
        App\Http\Controllers\KategoriServisController::class
    );

    Route::resource(
        'suku-cadang',
        SukuCadangController::class
    );

    Route::resource(
        'stok-masuk',
        StokMasukController::class
    );

    Route::resource(
        'transaksi-servis',
        TransaksiServisController::class
    );

    Route::get(
        '/transaksi-servis/{id}/nota',
        [TransaksiServisController::class, 'nota']
    )->name('transaksi-servis.nota');

    // Tambah/Hapus Servis
    Route::post(
        '/transaksi-servis/{id}/add-servis',
        [TransaksiServisController::class, 'addServis']
    )->name('transaksi-servis.add-servis');

    Route::delete(
        '/transaksi-servis/{orderId}/remove-servis/{detailServisId}',
        [TransaksiServisController::class, 'removeServis']
    )->name('transaksi-servis.remove-servis');

    // Tambah/Hapus Sparepart
    Route::post(
        '/transaksi-servis/{id}/add-sparepart',
        [TransaksiServisController::class, 'addSparepart']
    )->name('transaksi-servis.add-sparepart');

    Route::delete(
        '/transaksi-servis/{orderId}/remove-sparepart/{detailSukuCadangId}',
        [TransaksiServisController::class, 'removeSparepart']
    )->name('transaksi-servis.remove-sparepart');

    // Tambah/Hapus Jasa Tambahan
    Route::post(
        '/transaksi-servis/{id}/add-jasa-tambahan',
        [TransaksiServisController::class, 'addJasaTambahan']
    )->name('transaksi-servis.add-jasa-tambahan');

    Route::delete(
        '/transaksi-servis/{orderId}/remove-jasa-tambahan/{detailId}',
        [TransaksiServisController::class, 'removeJasaTambahan']
    )->name('transaksi-servis.remove-jasa-tambahan');

    Route::resource(
        'detail-suku-cadang',
        DetailSukuCadangController::class
    );

    Route::resource(
        'nota-pembayaran',
        NotaPembayaranController::class
    );

    Route::get(
    '/detail-suku-cadang/create/{id}',
    [DetailSukuCadangController::class, 'create']
    );

    Route::post(
        '/detail-suku-cadang/store',
        [DetailSukuCadangController::class, 'store']
    );

    Route::get(
    '/nota-pembayaran/create/{id}',
    [NotaPembayaranController::class, 'create']
    );

    Route::post(
        '/nota-pembayaran/store',
        [NotaPembayaranController::class, 'store']
    );

    Route::get(
    '/nota-pembayaran/print/{id}',
    [NotaPembayaranController::class, 'print']
    );
});


/*
|--------------------------------------------------------------------------
| PEMILIK
|--------------------------------------------------------------------------
*/

require __DIR__.'/pemilik.php';

Route::middleware([
    'auth',
    'role:pemilik'
])->group(function () {

    Route::get(
        '/dashboard-bengkel',
        [DashboardController::class, 'index']
    );

});


require __DIR__.'/auth.php';