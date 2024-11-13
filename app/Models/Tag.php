<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'user_id'
    ];

    /**
     * Relacionamento: A tag pode estar associada a muitas notas.
     */
    public function notas()
    {
        return $this->belongsToMany(Nota::class);
    }
}
