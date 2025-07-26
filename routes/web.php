<?php

use App\Http\Controllers\AnexoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

// Rota inicial
Route::get('/', fn() => redirect()->route('login'));

// Autenticação Breeze
require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard',[DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile
    Route::get('/profile',[ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas custom de Notas (devem vir antes do resource)
    Route::get('/notas/lixeira', [NotaController::class, 'lixeira'])
        ->name('notas.lixeira');

    Route::post('/notas/lixeira/{nota}/restaurar', [NotaController::class, 'restaurar'])
         ->withTrashed()
         ->name('notas.restaurar');

    Route::delete('/notas/lixeira/{nota}/excluir-permanente', [NotaController::class, 'excluirPermanente'])
         ->withTrashed()
         ->name('notas.excluir-permanente');

    Route::post('/notas/{nota}/complete',[NotaController::class, 'complete'])
        ->name('notas.complete');

    Route::get('/notas/{nota}/anexos',[NotaController::class, 'anexos'])
        ->name('notas.anexos');

    // Upload de anexos (separado de prefixo notas)
    Route::post('/anexos/upload',[AnexoController::class, 'Anexo'])
        ->name('anexos.upload');

    // Excluir anexo de nota
    Route::delete('/notas/{nota}/anexos/{anexo}',[AnexoController::class, 'destroy'])
        ->name('notas.anexos.destroy');

    // Resource “notas” — agora sem conflitos de URI
    Route::resource('notas', NotaController::class);

    // Outros resources
    Route::resource('tags', TagController::class);
    Route::resource('categorias', CategoriaController::class);
});
