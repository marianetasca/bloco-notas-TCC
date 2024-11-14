<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AnexoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Rota inicial
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de autenticação do Breeze
require __DIR__.'/auth.php';

// Rotas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notas resource (primeiro!)
    Route::resource('notas', NotaController::class);

    // Rotas específicas de notas (depois do resource)
    Route::get('/lixeira', [NotaController::class, 'lixeira'])->name('notas.lixeira');
    Route::post('/notas/{id}/restaurar', [NotaController::class, 'restaurar'])->name('notas.restaurar');
    Route::delete('/notas/{id}/excluir-permanente', [NotaController::class, 'excluirPermanente'])
        ->name('notas.excluir-permanente');
    Route::post('/notas/{nota}/complete', [NotaController::class, 'complete'])->name('notas.complete');
    Route::delete('/notas/{nota}/anexos/{anexo}', [AnexoController::class, 'destroy'])
        ->name('notas.anexos.destroy');

    // Outros resources
    Route::resource('tags', TagController::class);
    Route::resource('categorias', CategoriaController::class);
});
