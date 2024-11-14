<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    protected $fillable = [
        'nome_original',
        'caminho',
        'tipo_mime',
        'tamanho',
        'nota_id',
        'user_id'
    ];

    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
