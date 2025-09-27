<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationPreferencesController extends Controller
{
    /**
     * Exibir as preferências de notificação
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('notification-preferences', [
            'user' => $user,
            'preferencias' => $user->preferencias_notificacao
        ]);
    }

    /**
     * Atualizar as preferências de notificação
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'notificacoes_ativas' => 'boolean',
            'email' => 'boolean',
            'whatsapp' => 'boolean',
            'telefone' => 'nullable|string|max:20',
            'dias_antecedencia' => 'array',
            'dias_antecedencia.*' => 'integer|min:0|max:30',
            'horario_envio' => 'required|date_format:H:i',
        ]);

        $user = $request->user();

        // Atualizar campos básicos
        $user->notificacoes_ativas = $request->boolean('notificacoes_ativas');
        $user->telefone = $validated['telefone'] ?? null;

        // Atualizar preferências JSON
        $preferencias = [
            'email' => $request->boolean('email'),
            'whatsapp' => $request->boolean('whatsapp'),
            'dias_antecedencia' => $validated['dias_antecedencia'] ?? [7, 1, 0],
            'horario_envio' => $validated['horario_envio'],
        ];

        $user->preferencias_notificacao = $preferencias;
        $user->save();

        return back()->with('success', 'Preferências de notificação atualizadas com sucesso!');
    }
}
