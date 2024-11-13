<?php

namespace Database\Seeders;

use App\Models\Prioridade;
use Illuminate\Database\Seeder;

class PrioridadeSeeder extends Seeder
{
    public function run(): void
    {
        // Verifica se já existem prioridades
        if (Prioridade::count() > 0) {
            return;
        }

        // Cria as novas prioridades
        $prioridades = [
            [
                'user_id' => 1,
                'nome' => 'Baixa',
                'nivel' => 1,
                'cor' => '#4CAF50'
            ],
            [
                'user_id' => 1,
                'nome' => 'Média',
                'nivel' => 2,
                'cor' => '#FFC107'
            ],
            [
                'user_id' => 1,
                'nome' => 'Alta',
                'nivel' => 3,
                'cor' => '#F44336'
            ],
            [
                'user_id' => 1,
                'nome' => 'Urgente',
                'nivel' => 4,
                'cor' => '#B71C1C'
            ]
        ];

        foreach ($prioridades as $prioridade) {
            Prioridade::create($prioridade);
        }
    }
}
