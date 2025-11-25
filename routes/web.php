<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clogin;
use App\Http\Controllers\Cdashboard;
use App\Http\Controllers\Csiswa;
use App\Http\Controllers\Canggota;
use App\Http\Controllers\Cbuku;
use App\Http\Controllers\Ckategori;
use App\Http\Controllers\Crak;
use App\Http\Controllers\Cpinjam;


Route::middleware(['guest'])->group(function () {
    Route::get('/login', [Clogin::class, 'index'])->name('login');
    Route::post('/login', [Clogin::class, 'login_proses'])->name('login_proses');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/logout', [Clogin::class, 'logout'])->name('logout');
    Route::get('/dashboard', [Cdashboard::class, 'index'])->name('dashboard');
    
    // Route Anggota
    Route::get('/anggota', [Canggota::class, 'index'])->name('anggota.index');
    Route::post('/anggota', [Canggota::class, 'save'])->name('anggota.save');
    Route::put('/anggota/{id}', [Canggota::class, 'update'])->name('anggota.update');
    Route::delete('/anggota/{id}', [Canggota::class, 'destroy'])->name('anggota.destroy');
    Route::get('/anggota/cetak', [Canggota::class, 'cetak'])->name('anggota.cetak');
    Route::get('/anggota/excel', [Canggota::class, 'excel'])->name('anggota.excel');
    Route::get('/anggota/kartu/{id}', [Canggota::class, 'cetakKartu'])->name('anggota.kartu');

    // Route Buku - FIXED
Route::prefix('buku')->group(function () {
    Route::get('/', [Cbuku::class, 'index'])->name('buku.index');
    Route::get('/generate-kode', [Cbuku::class, 'generateKode'])->name('buku.generateKode');
    Route::get('/cetak', [Cbuku::class, 'cetak'])->name('buku.cetak');
    Route::get('/excel', [Cbuku::class, 'excel'])->name('buku.excel');
    Route::post('/save', [Cbuku::class, 'save'])->name('buku.save');
    Route::put('/update/{id}', [Cbuku::class, 'update'])->name('buku.update');
    Route::get('/test-excel', function() {
        return response('Test Excel')
        ->header('Content-Type', 'application/vnd.ms-excel')
        ->header('Content-Disposition', 'attachment;filename="test.xls"');
    });
    Route::delete('/destroy/{id}', [Cbuku::class, 'destroy'])->name('buku.destroy');
    Route::get('/api-table', [Cbuku::class, 'apiTableView'])->name('buku.api.table');
    });

    // Route Kategori
    Route::prefix('kategori')->group(function () {
        Route::get('/', [Ckategori::class, 'index'])->name('kategori.index');
        Route::post('/save', [Ckategori::class, 'save'])->name('kategori.save');
        Route::put('/update/{id}', [Ckategori::class, 'update'])->name('kategori.update');
        Route::delete('/destroy/{id}', [Ckategori::class, 'destroy'])->name('kategori.destroy');
        Route::get('/cetak', [Ckategori::class, 'cetak'])->name('kategori.cetak');
        Route::get('/excel', [Ckategori::class, 'excel'])->name('kategori.excel');
    });

    // Route Peminjaman
        Route::prefix('pinjam')->group(function () {
            Route::get('/', [Cpinjam::class, 'index'])->name('pinjam.index');
            Route::get('/add', [Cpinjam::class, 'add'])->name('pinjam.add');
            Route::post('/save', [Cpinjam::class, 'save'])->name('pinjam.save');
            Route::get('/view/{id}', [Cpinjam::class, 'view'])->name('pinjam.view');
            Route::put('/update/{id}', [Cpinjam::class, 'update'])->name('pinjam.update'); // ADD THIS
            Route::delete('/destroy/{id}', [Cpinjam::class, 'destroy'])->name('pinjam.destroy'); // ADD THIS
            Route::post('/kembali/{id_pinjam}/{id_buku}', [Cpinjam::class, 'kembali'])->name('pinjam.kembali');
        });
    // Route Rak Buku
    Route::prefix('rak')->group(function () {
        Route::get('/', [Crak::class, 'index'])->name('rak.index');
        Route::post('/save', [Crak::class, 'save'])->name('rak.save');
        Route::put('/update/{id}', [Crak::class, 'update'])->name('rak.update'); 
        Route::delete('/destroy/{id}', [Crak::class, 'destroy'])->name('rak.destroy');
    });


    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});