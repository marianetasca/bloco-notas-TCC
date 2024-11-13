<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('prioridades')->insert([
            [
                'nome' => 'Baixa',
                'cor' => '#4CAF50',  // Verde
                'nivel' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nome' => 'Média',
                'cor' => '#FFC107',  // Amarelo
                'nivel' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nome' => 'Alta',
                'cor' => '#F44336',  // Vermelho
                'nivel' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        DB::table('prioridades')->whereIn('nivel', [1, 2, 3])->delete();
    }
};
