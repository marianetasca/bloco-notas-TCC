<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nota_id',
        'user_id',
        'nome_original',
        'caminho',
        'tipo_mime',
        'tamanho'
    ];

    protected $casts = [
        'tamanho' => 'integer',
    ];

    // Relacionamento com Nota
    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor para URL do anexo
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->caminho);
    }

    // Accessor para tamanho formatado
    public function getTamanhoFormatadoAttribute()
    {
        $bytes = $this->tamanho;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Método para verificar se é imagem
    public function isImage()
    {
        return in_array(strtolower($this->tipo_mime), [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp'
        ]);
    }

    // Método para obter ícone baseado no tipo
    public function getIcone()
    {
        $mime = strtolower($this->tipo_mime);

        if (str_contains($mime, 'pdf')) {
            return 'fas fa-file-pdf text-danger';
        } elseif (str_contains($mime, 'word') || str_contains($mime, 'document')) {
            return 'fas fa-file-word text-primary';
        } elseif (str_contains($mime, 'excel') || str_contains($mime, 'spreadsheet')) {
            return 'fas fa-file-excel text-success';
        } elseif (str_contains($mime, 'image')) {
            return 'fas fa-file-image text-info';
        } else {
            return 'fas fa-file text-secondary';
        }
    }
}
