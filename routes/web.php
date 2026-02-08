<?php

use App\Http\Controllers\AnexoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationPreferencesController;
use App\Http\Controllers\CompartilharController;
use Illuminate\Support\Facades\Route;

/* ROTAS ESPECÍFICAS PRIMEIRO
// ROTAS GENÉRICAS DEPOIS*/

// Rota publica (Landing Page)
Route::get('/', function () {
    // Se o usuário já estiver logado, redireciona para as notas
    if (auth()->check()) {
        return redirect()->route('notas.index');
    }
    // Se não estiver logado, mostra a landing page
    return view('welcome');
})->name('welcome');

require __DIR__ . '/auth.php'; //autenticação breeze

// DEBUG: rota temporária que aceita qualquer método em /compartilhar-comprovante e registra o request
// (use para verificar se o navegador está realmente fazendo o POST e o que chega ao servidor)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

Route::match(['GET','POST','OPTIONS','PUT','DELETE'], '/compartilhar-comprovante', function (Request $request) {
    Log::info('compartilhar-comprovante debug', [
        'method' => $request->method(),
        'headers' => $request->headers->all(),
        'has_file' => $request->hasFile('arquivo'),
        'cookies' => $request->cookie()
    ]);

    return response()->json(['debug' => true, 'received' => true]);
})->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Rota pública para receber compartilhamentos (Web Share Target)
// Deve ser pública porque o navegador irá postar antes do usuário estar logado.
Route::post('/compartilhar-comprovante', [CompartilharController::class, 'receberCompartilhamento'])
    ->name('compartilhar.receber')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Página de teste (GET) com um formulário para POST manualmente
Route::get('/compartilhar-teste', function () {
    return view('compartilhar_teste');
});

// Rota temporária de debug: registra os headers e retorna 200 para verificar se o
// POST chega ao servidor (sem CSRF)
Route::post('/compartilhar-teste', function (\Illuminate\Http\Request $request) {
    \Log::info('compartilhar-teste received', [
        'path' => $request->path(),
        'method' => $request->method(),
        'headers' => $request->headers->all(),
        'has_file' => $request->hasFile('arquivo')
    ]);

    return response()->json(['ok' => true]);
})->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::middleware(['auth'])->group(function () { //auth porque o usuario precisa estar logado para acessar qualquer rota a baixo

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    Route::post('/notas/{nota}/concluido', [NotaController::class, 'concluido'])
        ->name('notas.concluido');

    // Upload e remoção via Dropzone
    Route::post('/anexos/upload', [AnexoController::class, 'upload'])->name('anexos.upload');
    Route::delete('/anexos/{anexo}', [AnexoController::class, 'remove'])->name('anexos.remove'); // Limpeza de anexos temporários
    Route::delete('/anexos/limpar-temporarios', [AnexoController::class, 'limparTemporarios'])->name('anexos.limpar-temporarios');

    // Exclusão de anexo específico de uma nota
    Route::delete('/notas/{nota}/anexos/{anexo}', [AnexoController::class, 'destroy'])->name('anexos.destroy');

    // Rotas especificas (notificações)
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::delete('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.delete-all');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Rotas genericas (notificações)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Preferências de notificação
    Route::get('/notification-preferences', [NotificationPreferencesController::class, 'edit'])->name('notification-preferences.edit');
    Route::put('/notification-preferences', [NotificationPreferencesController::class, 'update'])->name('notification-preferences.update');

    // Resources
    Route::resource('notas', NotaController::class);
    Route::resource('tags', TagController::class);
    Route::resource('categorias', CategoriaController::class);

    // Rota para receber compartilhamentos (PWA Share Target)
    // NOTE: agora a rota pública foi definida fora do grupo `auth` para que o browser
    // possa postar o arquivo mesmo que o usuário não esteja autenticado.

    // Rota para processar compartilhamento após login
    Route::get('/processar-compartilhamento', [CompartilharController::class, 'processarCompartilhamentoPendente'])
        ->middleware('auth')
        ->name('compartilhar.processar');
});
