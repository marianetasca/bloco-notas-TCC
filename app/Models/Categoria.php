<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Categoria extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'descricao', 'user_id'];

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}
