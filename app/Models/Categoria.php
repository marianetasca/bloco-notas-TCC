<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Categoria extends Model
{
    use HasFactory;

    // Relacionamento com notas
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}
