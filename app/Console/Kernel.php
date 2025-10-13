<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        Log::info('Método schedule() do Kernel executado');

        $schedule->command('notas:limpar-lixeira')->daily();


        // Verifica notas vencendo todo dia às 9h da manhã
        $schedule->command('notas:check-expiring')
            ->dailyAt('09:00')
            ->withoutOverlapping(); // Evita execuções simultâneas

    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
