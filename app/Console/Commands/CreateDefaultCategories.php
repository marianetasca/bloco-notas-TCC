<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Categoria;

class CreateDefaultCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:create-defaults';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default "Sem categoria" for all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            // Verifica se o usuário já tem a categoria "Sem categoria"
            $existingCategory = Categoria::where('user_id', $user->id)
                                       ->where('nome', 'Sem categoria')
                                       ->first();

            if (!$existingCategory) {
                Categoria::create([
                    'nome' => 'Sem categoria',
                    'user_id' => $user->id,
                ]);
                $this->info("Categoria padrão criada para usuário {$user->name}");
            }
        }

        $this->info('Categorias padrão criadas com sucesso!');
    }
}
