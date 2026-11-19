<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyReminder extends Notification
{
    use Queueable;

    public function __construct(
        public array $todayEvents,
        public array $todayPayments,
        public array $overduePayments,
        public array $todayTasks,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Kontanos — Resumen del día '.now()->format('d/m/Y'))
            ->greeting('Hola '.$notifiable->name.',');

        if (count($this->todayEvents) > 0) {
            $mail->line('**Eventos de hoy ('.count($this->todayEvents).'):**');
            foreach ($this->todayEvents as $event) {
                $mail->line('- '.$event['name'].' @ '.$event['venue'].' ('.$event['time'].')');
            }
        }

        if (count($this->todayPayments) > 0) {
            $mail->line('**Pagos que vencen hoy ('.count($this->todayPayments).'):**');
            foreach ($this->todayPayments as $payment) {
                $mail->line('- L '.number_format($payment['amount'], 2).' — '.$payment['client']);
            }
        }

        if (count($this->overduePayments) > 0) {
            $mail->line('**Pagos vencidos ('.count($this->overduePayments).'):**');
            foreach ($this->overduePayments as $payment) {
                $mail->line('- L '.number_format($payment['amount'], 2).' — '.$payment['client'].' (vencido '.$payment['days_overdue'].' días)');
            }
        }

        if (count($this->todayTasks) > 0) {
            $mail->line('**Tareas pendientes para hoy ('.count($this->todayTasks).'):**');
            foreach ($this->todayTasks as $task) {
                $mail->line('- '.$task['title'].' — '.$task['client']);
            }
        }

        if (count($this->todayEvents) === 0 && count($this->todayPayments) === 0
            && count($this->overduePayments) === 0 && count($this->todayTasks) === 0) {
            $mail->line('No hay eventos, pagos ni tareas pendientes para hoy.');
        }

        return $mail
            ->action('Ir al Dashboard', url('/dashboard'))
            ->line('Kontanos Publicidad LED');
    }
}
