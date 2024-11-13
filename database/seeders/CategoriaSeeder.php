<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\User;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        // Pega o primeiro usuário ou cria um novo se não existir
        $user = User::first() ?? User::factory()->create();

        // Cria a categoria padrão "Sem categoria"
        Categoria::create([
            'nome' => 'Sem categoria',
            'user_id' => $user->id, // Usa o ID do usuário em vez de null
        ]);

        // Outras categorias se necessário...
    }
}
