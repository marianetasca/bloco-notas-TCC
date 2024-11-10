<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prioridade;

class PrioridadeSeeder extends Seeder
{
    public function run(): void
    {
        $prioridades = [
            [
                'nome' => 'Baixa',
                'cor' => '#28a745',
                'nivel' => 1
            ],
            [
                'nome' => 'Média',
                'cor' => '#ffc107',
                'nivel' => 2
            ],
            [
                'nome' => 'Alta',
                'cor' => '#dc3545',
                'nivel' => 3
            ],
            [
                'nome' => 'Urgente',
                'cor' => '#9c27b0',
                'nivel' => 4
            ]
        ];

        foreach ($prioridades as $prioridade) {
            Prioridade::updateOrCreate(
                ['nivel' => $prioridade['nivel']],
                $prioridade
            );
        }
    }
}
