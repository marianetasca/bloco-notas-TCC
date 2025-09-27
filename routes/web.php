<?php

use App\Http\Controllers\AnexoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationPreferencesController;
use Illuminate\Support\Facades\Route;

// Rota inicial
Route::get('/', fn() => redirect()->route('login'));

// Autenticação Breeze
require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/only-verified', function () {
        return view('only-verified');
    })->middleware(['auth', 'verified']);
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas custom de Notas (devem vir antes do resource)
    Route::get('/notas/lixeira', [NotaController::class, 'lixeira'])
        ->name('notas.lixeira');

    Route::post('/notas/lixeira/{nota}/restaurar', [NotaController::class, 'restaurar'])
        ->withTrashed()
        ->name('notas.restaurar');

    Route::delete('/notas/lixeira/{nota}/excluir-permanente', [NotaController::class, 'excluirPermanente'])
        ->withTrashed()
        ->name('notas.excluir-permanente');

    Route::post('/notas/{nota}/complete', [NotaController::class, 'complete'])
        ->name('notas.complete');

    Route::post('/notas/{nota}/concluido', [NotaController::class, 'concluido'])
        ->name('notas.concluido');

    // Upload e remoção via Dropzone
    Route::post('/anexos/upload', [AnexoController::class, 'upload'])->name('anexos.upload');
    Route::delete('/anexos/{anexo}', [AnexoController::class, 'remove'])->name('anexos.remove');    // Limpeza de anexos temporários
    Route::delete('/anexos/limpar-temporarios', [AnexoController::class, 'limparTemporarios'])->name('anexos.limpar-temporarios');
    // Exclusão de anexo específico de uma nota
    Route::delete('/notas/{nota}/anexos/{anexo}', [AnexoController::class, 'destroy'])->name('anexos.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Preferências de notificação
    Route::get('/notifications', [NotificationPreferencesController::class, 'edit'])->name('notification-preferences.edit');
    Route::put('/notifications', [NotificationPreferencesController::class, 'update'])->name('notification-preferences.update');

    // Resource notas
    Route::resource('notas', NotaController::class);

    // Outros resources
    Route::resource('tags', TagController::class);
    Route::resource('categorias', CategoriaController::class);
});
