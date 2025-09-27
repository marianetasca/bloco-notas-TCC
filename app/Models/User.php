<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable //implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'notificacoes_ativas',
        'preferencias_notificacao',
        'telefone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notificacoes_ativas' => 'boolean',
            'preferencias_notificacao' => 'array', // JSON para array
        ];
    }
     /**
     * Verifica se o usuário quer receber notificações
     */
    public function recebeNotificacoes(): bool
    {
        return $this->notificacoes_ativas;
    }

    /**
     * Verifica se quer receber por email
     */
    public function recebeNotificacoesPorEmail(): bool
    {
        if (!$this->recebeNotificacoes()) {
            return false;
        }

        $preferencias = $this->preferencias_notificacao ?? [];
        return $preferencias['email'] ?? true; // Default true
    }

    /**
     * Verifica se quer receber por WhatsApp (futuro)
     */
    public function recebeNotificacoesPorWhatsapp(): bool
    {
        if (!$this->recebeNotificacoes()) {
            return false;
        }

        $preferencias = $this->preferencias_notificacao ?? [];
        return ($preferencias['whatsapp'] ?? false) && !empty($this->telefone);
    }

    /**
     * Configuração padrão de preferências
     */
    public function getPreferenciasNotificacaoAttribute($value)
    {
        $decoded = json_decode($value, true) ?? [];

        return array_merge([
            'email' => true,
            'whatsapp' => false,
            'dias_antecedencia' => [7, 1, 0], // 7 dias, 1 dia, no dia
            'horario_envio' => '09:00',
        ], $decoded);
    }
}

