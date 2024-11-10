<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'conteudo',
        'categoria_id',
        'user_id'
    ];

    protected $casts = [
        'data_entrega' => 'date'
    ];

    /**
     * Relacionamento: A nota pertence a um usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: A nota pertence a uma categoria.
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Relacionamento: A nota pode ter muitas tags.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'nota_tag');
    }

    /**
     * Relacionamento: A nota pertence a uma prioridade.
     */
    public function prioridade()
    {
        return $this->belongsTo(Prioridade::class);
    }

    public function isAtrasada()
    {
        return $this->data_entrega && $this->data_entrega->isPast();
    }

    public function isPróximaDoVencimento()
    {
        if (!$this->data_entrega) return false;
        $diasParaVencer = now()->diffInDays($this->data_entrega, false);
        return $diasParaVencer >= 0 && $diasParaVencer <= 3;
    }
}
