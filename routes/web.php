<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Kaprodi\AdminDashboardController as AdminDashboardController;
use App\Http\Controllers\Kaprodi\ManajemenMhsController as ManajemenMhsController;
use App\Http\Controllers\Kaprodi\MahasiswaVerificationController;

use App\Http\Controllers\Dosen\DosenDashboardController as DosenDashboardController;

use App\Http\Controllers\Mahasiswa\MhsDashboardController as MhsDashboardController;
use App\Http\Controllers\Mahasiswa\PengajuanController as PengajuanController;

use App\Http\Controllers\Super\SuperDashboardController as SuperDashboardController;
use App\Http\Controllers\Super\ProdiController as ProdiController;
use App\Http\Controllers\Super\BidangStudiController as BidangStudiController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    $role = Auth::user()->role;

    if ($role === 'kaprodi') {
        return redirect()->route('kaprodi.dashboard');
    } elseif ($role === 'dosen') {
        return redirect()->route('dosen.dashboard');
    } elseif ($role === 'super_admin'){
        return redirect()->route('super_admin.dashboard');
    }else{
        return "Halo Mahasiswa! Ini halaman dashboard kamu.";
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:kaprodi'])->prefix('kaprodi')->name('kaprodi.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/mahasiswa', [ManajemenMhsController::class, 'daftar'])->name('daftar-mhs');
    Route::get('/mahasiswa/create', [ManajemenMhsController::class, 'create'])->name('mahasiswa.create');
    Route::post('/mahasiswa/store', [ManajemenMhsController::class, 'store'])->name('mahasiswa.store');
    Route::get('/mahasiswa/{id}/edit', [ManajemenMhsController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/mahasiswa/{id}', [ManajemenMhsController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{id}', [ManajemenMhsController::class, 'destroy'])->name('mahasiswa.destroy');

    Route::patch('/mahasiswa/{mahasiswa}/verify', [MahasiswaVerificationController::class, 'verify'])
        ->name('mahasiswa.verify');

    Route::get('/dosen', [App\Http\Controllers\Kaprodi\ManajemenDosenController::class, 'index'])->name('dosen.index');

    Route::get('/pengajuan', [App\Http\Controllers\Kaprodi\PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/{id}', [App\Http\Controllers\Kaprodi\PengajuanController::class, 'show'])->name('pengajuan.show');
    Route::patch('/pengajuan/{id}/update-status', [App\Http\Controllers\Kaprodi\PengajuanController::class, 'updateStatus'])->name('pengajuan.update');
});

Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
}); 

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [MhsDashboardController::class, 'index'])->name('dashboard');

    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/mahasiswa/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
});

Route::middleware(['auth', 'role:super_admin'])->prefix('super_admin')->name('super_admin.')->group(function () {
    Route::get('/dashboard', [SuperDashboardController::class, 'index'])->name('dashboard');

    Route::get('/prodi', [ProdiController::class, 'index'])->name('prodi.index');
    Route::get('/prodi/create', [ProdiController::class, 'create'])->name('prodi.create');
    Route::post('/prodi/store', [ProdiController::class, 'store'])->name('prodi.store');
    Route::get('/prodi/{id}/edit', [ProdiController::class, 'edit'])->name('prodi.edit');
    Route::put('/prodi/{id}', [ProdiController::class, 'update'])->name('prodi.update');
    Route::delete('/prodi/delete/{id}', [ProdiController::class, 'destroy'])->name('prodi.destroy');

    Route::get('/bidang-studi', [BidangStudiController::class, 'index'])->name('bidang-studi.index');
    Route::get('/bidang-studi/create', [BidangStudiController::class, 'create'])->name('bidang-studi.create');
    Route::post('/bidang-studi/store', [BidangStudiController::class, 'store'])->name('bidang-studi.store');
    Route::get('/bidang-studi/{id}/edit', [BidangStudiController::class, 'edit'])->name('bidang-studi.edit');
    Route::put('/bidang-studi/{id}', [BidangStudiController::class, 'update'])->name('bidang-studi.update');
    Route::delete('/bidang-studi/delete/{id}', [BidangStudiController::class, 'destroy'])->name('bidang-studi.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
