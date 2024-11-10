<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::controller(LoginController::class)->group(function () {
  Route::get('/login', 'index')->name('login.index');
  Route::post('/login', 'store')->name('login.store');
  Route::get('/logout', 'destroy')->name('login.destroy');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('notas', NotaController::class);

    Route::resource('categorias', CategoriaController::class);

    Route::resource('tags', TagController::class);

    Route::patch('/notas/{nota}/complete', [NotaController::class, 'complete'])->name('notas.complete');

    Route::delete('/notas/{nota}/anexos/{anexo}', [NotaController::class, 'destroyAnexo'])
        ->name('notas.anexos.destroy')
        ->middleware(['auth']);
});

require __DIR__.'/auth.php';
