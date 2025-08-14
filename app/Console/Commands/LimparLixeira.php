<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Nota;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class LimparLixeira extends Command
{
    /**
     * The name and signature of the console command.
     *
     */
    protected $signature = 'notas:limpar-lixeira';

    /**
     * The console command description.
     *
     */
    protected $description = 'Exclui permanentemente notas que estão na lixeira há mais de 30 dias';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Comando notas:limpar-lixeira executado automaticamente.');

        $prazo = Carbon::now()->subDays(30);

        $notas = Nota::onlyTrashed()
            ->where('deleted_at', '<', $prazo)
            ->get();

        foreach ($notas as $nota) {
            // Exclui os anexos
            foreach ($nota->anexos as $anexo) {
                Storage::disk('public')->delete($anexo->caminho);
                $anexo->delete();
            }

            $nota->forceDelete();
        }

        $this->info("Notas antigas excluídas permanentemente: {$notas->count()}");
    }
}
