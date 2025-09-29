<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clogin;
use App\Http\Controllers\Cdashboard;
use App\Http\Controllers\Csiswa;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [Clogin::class, 'index'])->name('login');
    Route::post('/login', [Clogin::class, 'login_proses'])->name('login_proses');
});

// Route::middleware(['cek_level:admin'])->group(function () {
//     Route::resource('anggota', Canggota::class);
// });


Route::middleware(['auth'])->group(function () {
	Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/logout', [Clogin::class, 'logout'])->name('logout');
    Route::get('/dashboard', [Cdashboard::class, 'index'])->name('dashboard');
    
    // Route::resource('/siswa', Csiswa::class);


    Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
    })->name('logout');

});
