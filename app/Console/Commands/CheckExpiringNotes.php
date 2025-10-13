<?php

namespace App\Console\Commands;

use App\Models\Nota;
use App\Models\User;
use App\Notifications\NotaExpiringNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckExpiringNotes extends Command
{
    protected $signature = 'notes:check-expiring';
    protected $description = 'Verifica notas próximas do vencimento e envia notificações';

    public function handle()
    {
        $today = Carbon::today();
        $notificationsSent = 0;

        // Buscar usuários com notificações ativas
        $usersWithNotifications = User::where('notificacoes_ativas', true)->get();

        foreach ($usersWithNotifications as $user) {
            $preferencias = $user->preferencias_notificacao;
            $diasAntecedencia = $preferencias['dias_antecedencia'] ?? [7, 1, 0];

            foreach ($diasAntecedencia as $dias) {
                // Garantir que $dias seja um inteiro
                $dias = (int) $dias;
                $dataAlvo = $today->copy()->addDays($dias);

                // Buscar notas do usuário que vencem na data alvo
                $notasVencendo = Nota::where('user_id', $user->id)
                    ->whereDate('data_vencimento', $dataAlvo)
                    ->where('concluido', false)
                    ->whereNull('completed_at')
                    ->get();

                foreach ($notasVencendo as $nota) {
                    if ($user->recebeNotificacoesPorEmail()) {
                        $user->notify(new NotaExpiringNotification($nota, $dias));
                        $notificationsSent++;
                        $this->info("Notificação enviada para {$user->email} - Nota: {$nota->titulo} (vence em {$dias} dias)");
                    }
                }
            }
        }

        $this->info("Total de notificações enviadas: {$notificationsSent}");

        return Command::SUCCESS;
    }
}
