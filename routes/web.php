<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clogin;
use App\Http\Controllers\Cdashboard;
use App\Http\Controllers\Csiswa;
use App\Http\Controllers\Canggota;
use App\Http\Controllers\Cbuku;
use App\Http\Controllers\Ckategori;

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


    Route::prefix('buku')->group(function () {
    Route::get('/', [Cbuku::class, 'index'])->name('buku.index');
    Route::post('/save', [Cbuku::class, 'save'])->name('buku.save');
    Route::put('/update/{id}', [Cbuku::class, 'update'])->name('buku.update');
    Route::delete('/destroy/{id}', [Cbuku::class, 'destroy'])->name('buku.destroy');
    });
    Route::prefix('kategori')->middleware(['auth'])->group(function () {
    Route::get('/', [Ckategori::class, 'index'])->name('kategori.index');
    Route::post('/save', [Ckategori::class, 'save'])->name('kategori.save');
    Route::put('/update/{id}', [Ckategori::class, 'update'])->name('kategori.update');
    Route::delete('/destroy/{id}', [Ckategori::class, 'destroy'])->name('kategori.destroy');
    });
    

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});