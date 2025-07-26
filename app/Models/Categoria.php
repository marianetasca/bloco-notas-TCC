<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Categoria extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'descricao', 'user_id', 'is_default'];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}
