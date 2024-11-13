<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Categoria;

class UserObserver
{
    public function created(User $user)
    {
        Categoria::create([
            'nome' => 'Sem categoria',
            'user_id' => $user->id,
        ]);
    }
} 
