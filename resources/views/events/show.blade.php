@extends('layouts.app')

@section('title', $event->name)
@section('page-title', 'Detalle del Evento')
@section('page-subtitle', $event->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-600">
        <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Volver a eventos
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        {{-- Event header --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    @php
                        $typeIcons = ['boda' => 'favorite', 'quinceanera' => 'cake', 'cumpleanos' => 'cake', 'corporativo' => 'business', 'concierto' => 'music_note', 'festival' => 'festival', 'deportivo' => 'sports_soccer', 'religioso' => 'church', 'politico' => 'campaign', 'graduacion' => 'school', 'feria' => 'storefront', 'otro' => 'celebration'];
                    @endphp
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size:24px">{{ $typeIcons[$event->type] ?? 'celebration' }}</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $event->name }}</h3>
                        <p class="text-sm text-gray-500">{{ \App\Models\Event::TYPES[$event->type] ?? $event->type }}</p>
                    </div>
                </div>
                <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center gap-1 px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    <span class="material-symbols-outlined" style="font-size:16px">edit</span> Editar
                </a>
            </div>

            @php
                $stColors = ['cotizado' => 'bg-slate-100 text-slate-700', 'confirmado' => 'bg-blue-100 text-blue-700', 'en_curso' => 'bg-amber-100 text-amber-700', 'completado' => 'bg-emerald-100 text-emerald-700', 'cancelado' => 'bg-red-100 text-red-700'];
            @endphp
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $stColors[$event->status] ?? '' }} mb-4">
                {{ \App\Models\Event::STATUSES[$event->status] ?? $event->status }}
            </span>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-400 text-xs">Fecha inicio</span>
                    <p class="font-medium text-gray-700">{{ $event->start_date->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <span class="text-gray-400 text-xs">Fecha fin</span>
                    <p class="font-medium text-gray-700">{{ $event->end_date->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <span class="text-gray-400 text-xs">Lugar</span>
                    <p class="font-medium text-gray-700">{{ $event->venue }}</p>
                </div>
                @if($event->venue_address)
                <div>
                    <span class="text-gray-400 text-xs">Dirección</span>
                    <p class="font-medium text-gray-700">{{ $event->venue_address }}</p>
                </div>
                @endif
                @if($event->municipality)
                <div>
                    <span class="text-gray-400 text-xs">Municipio</span>
                    <p class="font-medium text-gray-700">{{ $event->municipality->name }}</p>
                </div>
                @endif
                @if($event->screen)
                <div>
                    <span class="text-gray-400 text-xs">Pantalla</span>
                    <p class="font-medium text-gray-700">{{ $event->screen->name }}</p>
                </div>
                @endif
            </div>

            @if($event->description)
            <div class="mt-4 p-3 bg-gray-50 rounded-xl">
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $event->description }}</p>
            </div>
            @endif

            @if($event->technical_notes)
            <div class="mt-4 p-3 bg-amber-50 rounded-xl">
                <h4 class="text-xs font-semibold text-amber-700 mb-1">Notas técnicas</h4>
                <p class="text-sm text-amber-800 whitespace-pre-line">{{ $event->technical_notes }}</p>
            </div>
            @endif
        </div>

        {{-- Pricing --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600" style="font-size:20px">payments</span>
                Información Financiera
            </h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-[10px] text-gray-400 uppercase font-semibold">Cotizado</p>
                    <p class="text-lg font-bold text-gray-800 mt-1">L {{ number_format($event->quoted_price ?? 0, 2) }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-[10px] text-gray-400 uppercase font-semibold">Precio final</p>
                    <p class="text-lg font-bold text-gray-800 mt-1">L {{ number_format($event->final_price ?? 0, 2) }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-[10px] text-gray-400 uppercase font-semibold">Anticipo</p>
                    <p class="text-lg font-bold text-gray-800 mt-1">L {{ number_format($event->deposit_amount ?? 0, 2) }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-[10px] text-gray-400 uppercase font-semibold">Anticipo pagado</p>
                    <p class="text-lg font-bold mt-1 {{ $event->deposit_paid ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $event->deposit_paid ? 'Sí' : 'No' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        @if($event->contact_name || $event->contact_phone)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px">contact_phone</span> Contacto
            </h4>
            @if($event->contact_name)
                <p class="font-medium text-gray-800">{{ $event->contact_name }}</p>
            @endif
            @if($event->contact_phone)
                <p class="text-sm text-gray-500 mt-1">{{ $event->contact_phone }}</p>
                <a href="https://wa.me/504{{ preg_replace('/\D/', '', $event->contact_phone) }}" target="_blank"
                   class="inline-flex items-center gap-1 text-sm text-green-600 font-medium mt-2 hover:text-green-700">
                    <span class="material-symbols-outlined" style="font-size:16px">chat</span> WhatsApp
                </a>
            @endif
        </div>
        @endif

        @if($event->client)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px">business</span> Cliente
            </h4>
            <p class="font-medium text-gray-800">{{ $event->client->business_name }}</p>
            <a href="{{ route('clients.show', $event->client) }}" class="inline-flex items-center gap-1 text-sm text-brand-600 font-medium mt-2 hover:text-brand-700">
                Ver ficha <span class="material-symbols-outlined" style="font-size:14px">arrow_forward</span>
            </a>
        </div>
        @endif

        @if($event->notes)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-sm font-bold text-gray-700 mb-3">Notas</h4>
            <p class="text-sm text-gray-600 whitespace-pre-line">{{ $event->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
