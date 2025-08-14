<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nota extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titulo',
        'conteudo',
        'categoria_id',
        'prioridade_id',
        'data_vencimento',
        'user_id',
        'concluido',
        'completed_at'
    ];

    protected $casts = [
        'data_vencimento' => 'datetime',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'completed_at' => 'datetime',
        'concluido' => 'boolean', //para garantir que seja boolean

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
        return $this->belongsToMany(Tag::class, 'nota_tag', 'nota_id', 'tag_id');
    }

    /**
     * Relacionamento: A nota pertence a uma prioridade.
     */
    public function prioridade()
    {
        return $this->belongsTo(Prioridade::class);
    }

    public function diasRestantes()
    {
        return now()->diffInDays($this->data_vencimento, false);
    }


    public function anexos()
    {
        return $this->hasMany(Anexo::class);
    }

    public function tempoDesdeConclusao(): ?string
    {
        if (! $this->completed_at instanceof \Carbon\Carbon) {
            return null;
        }


        $dias = now()->diffInDays($this->completed_at, false);

        return match (true) {
            $dias == 0 => 'Concluída hoje',
            $dias == 1 => 'Concluída há 1 dia',
            default     => "Concluída há {$dias} dias",
        };
    }

        //   Verifica se a nota tem anexos.

    public function hasAnexos()
    {
        return $this->anexos()->count() > 0;
    }

    /**
     * Conta total de anexos.
     */
    public function getTotalAnexosAttribute()
    {
        return $this->anexos()->count();
    }


    public function scopeComAnexos($query)
    {
        return $query->has('anexos');
    }

    /**
     * Scope para notas sem anexos.
     */
    public function scopeSemAnexos($query)
    {
        return $query->doesntHave('anexos');
    }
}
