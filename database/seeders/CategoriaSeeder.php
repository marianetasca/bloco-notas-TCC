<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = \App\Models\User::all();

        foreach ($usuarios as $usuario) {
            Categoria::create([
                'nome' => 'Sem categoria',
                'user_id' => $usuario->id
            ]);
        }
    }
}
