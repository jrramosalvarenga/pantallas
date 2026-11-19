<?php

namespace App\Http\Controllers;

use App\Models\ClientTask;
use App\Models\Event;
use App\Models\Payment;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        return view('calendar.index');
    }

    public function events(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $calendarEvents = [];

        $payments = Payment::with('client', 'contract')
            ->whereBetween('due_date', [$start, $end])
            ->get();

        foreach ($payments as $payment) {
            $color = match ($payment->status) {
                'pagado' => '#10b981',
                'vencido' => '#ef4444',
                'parcial' => '#f59e0b',
                default => '#6366f1',
            };

            $calendarEvents[] = [
                'id' => 'payment-'.$payment->id,
                'title' => 'L '.number_format($payment->amount, 2).' — '.($payment->client->business_name ?? 'Sin cliente'),
                'start' => $payment->due_date->format('Y-m-d'),
                'color' => $color,
                'type' => 'payment',
                'url' => route('payments.index', ['search' => $payment->client->business_name ?? '']),
                'extendedProps' => [
                    'type' => 'payment',
                    'status' => $payment->status,
                    'icon' => 'payments',
                ],
            ];
        }

        $tasks = ClientTask::with('client')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$start, $end])
            ->get();

        foreach ($tasks as $task) {
            $color = match ($task->status) {
                'completada' => '#10b981',
                'cancelada' => '#9ca3af',
                'en_progreso' => '#f59e0b',
                default => $task->is_overdue ? '#ef4444' : '#8b5cf6',
            };

            $calendarEvents[] = [
                'id' => 'task-'.$task->id,
                'title' => $task->title.' — '.($task->client->business_name ?? ''),
                'start' => $task->due_date->format('Y-m-d'),
                'color' => $color,
                'type' => 'task',
                'url' => route('tasks.show', $task),
                'extendedProps' => [
                    'type' => 'task',
                    'status' => $task->status,
                    'icon' => 'task_alt',
                ],
            ];
        }

        $events = Event::with('client')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end]);
            })
            ->get();

        foreach ($events as $event) {
            $color = match ($event->status) {
                'confirmado' => '#0ea5e9',
                'en_curso' => '#f59e0b',
                'completado' => '#10b981',
                'cancelado' => '#9ca3af',
                default => '#64748b',
            };

            $calendarEvents[] = [
                'id' => 'event-'.$event->id,
                'title' => $event->name.($event->venue ? ' @ '.$event->venue : ''),
                'start' => $event->start_date->format('Y-m-d\TH:i:s'),
                'end' => $event->end_date->format('Y-m-d\TH:i:s'),
                'color' => $color,
                'type' => 'event',
                'url' => route('events.show', $event),
                'extendedProps' => [
                    'type' => 'event',
                    'status' => $event->status,
                    'icon' => 'celebration',
                ],
            ];
        }

        return response()->json($calendarEvents);
    }
}
