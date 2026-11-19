<?php

namespace App\Console\Commands;

use App\Models\ClientTask;
use App\Models\Event;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\DailyReminder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:send-daily-reminders')]
#[Description('Envía resumen diario por correo con eventos, pagos y tareas')]
class SendDailyReminders extends Command
{
    public function handle(): void
    {
        $today = now()->toDateString();

        $todayEvents = Event::whereDate('start_date', $today)
            ->whereIn('status', ['confirmado', 'en_curso'])
            ->get()
            ->map(fn ($e) => [
                'name' => $e->name,
                'venue' => $e->venue,
                'time' => $e->start_date->format('H:i').' - '.$e->end_date->format('H:i'),
            ])
            ->toArray();

        $todayPayments = Payment::with('client')
            ->whereDate('due_date', $today)
            ->where('status', 'pendiente')
            ->get()
            ->map(fn ($p) => [
                'amount' => $p->amount,
                'client' => $p->client->business_name ?? 'Sin cliente',
            ])
            ->toArray();

        $overduePayments = Payment::with('client')
            ->where('status', 'pendiente')
            ->whereDate('due_date', '<', $today)
            ->orderBy('due_date')
            ->limit(20)
            ->get()
            ->map(fn ($p) => [
                'amount' => $p->amount,
                'client' => $p->client->business_name ?? 'Sin cliente',
                'days_overdue' => $p->due_date->diffInDays(now()),
            ])
            ->toArray();

        $todayTasks = ClientTask::with('client')
            ->whereDate('due_date', $today)
            ->whereIn('status', ['pendiente', 'en_progreso'])
            ->get()
            ->map(fn ($t) => [
                'title' => $t->title,
                'client' => $t->client->business_name ?? 'Sin cliente',
            ])
            ->toArray();

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new DailyReminder(
                $todayEvents,
                $todayPayments,
                $overduePayments,
                $todayTasks,
            ));
        }

        $this->info('Recordatorios enviados a '.$admins->count().' administradores.');
    }
}
