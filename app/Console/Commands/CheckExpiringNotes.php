<?php

namespace App\Console\Commands;

use App\Models\Nota;
use App\Models\User;
use App\Notifications\NotaExpiringNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckExpiringNotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notes:check-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica notas próximas do vencimento e envia notificações';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $notificationsSent = 0;

        // Buscar todos os usuários que têm notificações ativas
        $usersWithNotifications = User::where('notificacoes_ativas', true)
            ->with(['notas' => function($query) {
                $query->whereNull('concluida_em')
                      ->whereNotNull('data_vencimento');
            }])
            ->get();

        foreach ($usersWithNotifications as $user) {
            $preferencias = $user->preferencias_notificacao;
            $diasAntecedencia = $preferencias['dias_antecedencia'] ?? [7, 1, 0];

            foreach ($diasAntecedencia as $dias) {
                $dataAlvo = $today->copy()->addDays($dias);

                $notasVencendo = $user->notas()
                    ->whereDate('data_vencimento', $dataAlvo)
                    ->whereNull('concluida_em')
                    ->get();

                foreach ($notasVencendo as $nota) {
                    $user->notify(new NotaExpiringNotification($nota, $dias));
                    $notificationsSent++;
                }
            }
        }

        $this->info("Notificações enviadas: {$notificationsSent}");

        return Command::SUCCESS;
    }
}
