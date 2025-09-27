<?php

namespace App\Notifications;

use App\Models\Nota;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotaExpiringNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Nota $nota,
        public int $diasRestantes
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
   public function via(object $notifiable): array
    {
        // Verificar se o usuário quer receber notificações
        if (!$notifiable->recebeNotificacoes()) {
            return []; // Não enviar nenhuma notificação
        }

        $channels = ['database']; // Sempre salva no database

        // Adicionar canais baseado nas preferências
        if ($notifiable->recebeNotificacoesPorEmail()) {
            $channels[] = 'mail';
        }

        if ($notifiable->recebeNotificacoesPorWhatsapp()) {
            // $channels[] = 'vonage'; // Para o futuro
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        // POR ESTA:
        $url = url('/notas?highlight=' . $this->nota->id);

        $subject = $this->diasRestantes === 0
            ? 'Nota vencendo hoje!'
            : "Nota vence em {$this->diasRestantes} dias";

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Olá!')
            ->line("Sua nota \"{$this->nota->titulo}\" está próxima do vencimento.")
            ->lineIf($this->diasRestantes === 0, '⚠️ **Esta nota vence hoje!**')
            ->lineIf($this->diasRestantes > 0, "📅 Vence em: **{$this->diasRestantes} dias**")
            ->line("Data de vencimento: {$this->nota->data_vencimento->format('d/m/Y')}")
            ->action('Ver Nota', $url)
            ->line('Não deixe tarefas importantes passarem despercebidas!')
            ->line('Atenciosamente,')
            ->salutation(config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'nota_id' => $this->nota->id,
            'titulo' => $this->nota->titulo,
            'dias_restantes' => $this->diasRestantes,
            'data_vencimento' => $this->nota->data_vencimento,
            'url' => route('notas.index', $this->nota), ///////////////
            'tipo' => 'vencimento',
        ];
    }
}
