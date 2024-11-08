<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Nota extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'titulo', 'conteudo', 'categoria_id'];

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

}
