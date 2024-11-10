<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    protected $fillable = [
        'nota_id',
        'nome_original',
        'caminho',
        'tipo_mime',
        'tamanho'
    ];

    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }
}
