<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prioridade extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cor',
        'nivel'
    ];

    /**
     * Relacionamento: Uma prioridade pode ter muitas notas
     */
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

    /**
     * Acessor para obter a classe CSS baseada na cor // nao levei adiante essa ideia
     */
    public function getClasseCssAttribute()
    {
        return match($this->nivel) {
            1 => 'bg-success',   // Baixa
            2 => 'bg-warning',   // Média
            3 => 'bg-danger',    // Alta
            default => 'bg-secondary'
        };
    }

    /**
     * Ordenar prioridades por nível
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('nivel', 'asc');
    }
}
