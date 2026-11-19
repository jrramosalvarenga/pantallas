@extends('layouts.app')

@section('title', 'Calendario')
@section('page-title', 'Calendario')
@section('page-subtitle', 'Eventos, tareas y pagos')

@push('styles')
<style>
    .fc { font-family: 'Inter', sans-serif; font-size: 13px; }
    .fc .fc-toolbar-title { font-size: 1.1rem; font-weight: 700; }
    .fc .fc-button { border-radius: 8px !important; font-size: 12px; padding: 6px 14px; font-weight: 600; border: 1px solid #e5e7eb; background: white; color: #374151; }
    .fc .fc-button:hover { background: #f3f4f6; }
    .fc .fc-button-active { background: #4f46e5 !important; border-color: #4f46e5 !important; color: white !important; }
    .fc .fc-daygrid-day-number { font-size: 12px; font-weight: 600; color: #6b7280; padding: 4px 8px; }
    .fc .fc-daygrid-day.fc-day-today { background: #eef2ff !important; }
    .fc .fc-event { border-radius: 6px; padding: 2px 6px; font-size: 11px; border: none; cursor: pointer; }
    .fc .fc-col-header-cell-cushion { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #6b7280; }
    .fc .fc-scrollgrid { border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
    .fc th, .fc td { border-color: #f3f4f6 !important; }
</style>
@endpush

@section('content')
{{-- Legend --}}
<div class="flex flex-wrap items-center gap-4 mb-6">
    <div class="flex items-center gap-1.5">
        <span class="w-3 h-3 rounded-full bg-[#6366f1]"></span>
        <span class="text-xs text-gray-600">Pagos pendientes</span>
    </div>
    <div class="flex items-center gap-1.5">
        <span class="w-3 h-3 rounded-full bg-[#10b981]"></span>
        <span class="text-xs text-gray-600">Pagos realizados</span>
    </div>
    <div class="flex items-center gap-1.5">
        <span class="w-3 h-3 rounded-full bg-[#ef4444]"></span>
        <span class="text-xs text-gray-600">Vencidos</span>
    </div>
    <div class="flex items-center gap-1.5">
        <span class="w-3 h-3 rounded-full bg-[#8b5cf6]"></span>
        <span class="text-xs text-gray-600">Tareas</span>
    </div>
    <div class="flex items-center gap-1.5">
        <span class="w-3 h-3 rounded-full bg-[#0ea5e9]"></span>
        <span class="text-xs text-gray-600">Eventos</span>
    </div>
    <div class="ml-auto flex items-center gap-2">
        <a href="{{ route('events.create') }}" class="inline-flex items-center gap-1 bg-brand-600 text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-brand-700 transition">
            <span class="material-symbols-outlined" style="font-size:16px">celebration</span> Evento
        </a>
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-1 bg-purple-600 text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-purple-700 transition">
            <span class="material-symbols-outlined" style="font-size:16px">add_task</span> Tarea
        </a>
    </div>
</div>

{{-- Calendar --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div id="calendar"></div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.11/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            list: 'Lista'
        },
        events: function(info, successCallback, failureCallback) {
            fetch('{{ route("calendar.events") }}?start=' + info.startStr + '&end=' + info.endStr)
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(err => failureCallback(err));
        },
        eventClick: function(info) {
            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.location.href = info.event.url;
            }
        },
        height: 'auto',
        dayMaxEvents: 4,
        navLinks: true,
        editable: false,
    });
    calendar.render();
});
</script>
@endpush
