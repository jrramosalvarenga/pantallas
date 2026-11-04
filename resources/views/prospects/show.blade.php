@extends('layouts.app')

@section('title', $prospect->business_name)
@section('page-title', $prospect->business_name)
@section('page-subtitle', 'Detalle del prospecto')

@section('content')
<div class="mb-4">
    <a href="{{ route('prospects.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-600">
        <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Volver a prospectos
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left column: Info + Pitch --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Prospect info card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-4">
                    @if($prospect->logo_path)
                        <img src="{{ asset('storage/' . $prospect->logo_path) }}" class="w-16 h-16 rounded-xl object-cover" alt="">
                    @else
                        <div class="w-16 h-16 bg-brand-50 text-brand-600 rounded-xl flex items-center justify-center text-xl font-bold">
                            {{ strtoupper(substr($prospect->business_name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $prospect->business_name }}</h3>
                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                            @php
                                $statusColors = [
                                    'por_visitar' => 'bg-slate-100 text-slate-700',
                                    'contactado' => 'bg-blue-100 text-blue-700',
                                    'interesado' => 'bg-amber-100 text-amber-700',
                                    'negociando' => 'bg-purple-100 text-purple-700',
                                    'firmado' => 'bg-emerald-100 text-emerald-700',
                                    'no_interesado' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$prospect->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $prospect->status_label }}
                            </span>
                            @if($prospect->priority)
                                @php $prioColors = ['A' => 'bg-red-100 text-red-700', 'B' => 'bg-amber-100 text-amber-700', 'C' => 'bg-gray-100 text-gray-600']; @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $prioColors[$prospect->priority] ?? '' }}">
                                    Prioridad {{ $prospect->priority }}
                                </span>
                            @endif
                            @if($prospect->category)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-brand-50 text-brand-700">{{ $prospect->category }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('prospects.edit', $prospect) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-600 border border-gray-200 px-3 py-1.5 rounded-lg hover:border-brand-300 transition">
                    <span class="material-symbols-outlined" style="font-size:16px">edit</span> Editar
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                @if($prospect->contact_name)
                <div>
                    <span class="text-gray-400">Contacto</span>
                    <p class="font-medium text-gray-700">{{ $prospect->contact_name }}</p>
                </div>
                @endif
                @if($prospect->phone)
                <div>
                    <span class="text-gray-400">Teléfono</span>
                    <p class="font-medium text-gray-700">
                        <a href="https://wa.me/504{{ preg_replace('/\D/', '', $prospect->phone) }}" target="_blank" class="hover:text-green-600 flex items-center gap-1">
                            {{ $prospect->phone }}
                            <span class="material-symbols-outlined text-green-500" style="font-size:16px">chat</span>
                        </a>
                    </p>
                </div>
                @endif
                @if($prospect->address)
                <div>
                    <span class="text-gray-400">Dirección</span>
                    <p class="font-medium text-gray-700">{{ $prospect->address }}</p>
                </div>
                @endif
                @if($prospect->municipality)
                <div>
                    <span class="text-gray-400">Municipio</span>
                    <p class="font-medium text-gray-700">{{ $prospect->municipality->name }}</p>
                </div>
                @endif
                @if($prospect->origin)
                <div>
                    <span class="text-gray-400">Origen/Zona</span>
                    <p class="font-medium text-gray-700">{{ $prospect->origin }}</p>
                </div>
                @endif
                @if($prospect->nearby_screen)
                <div>
                    <span class="text-gray-400">Pantalla cercana</span>
                    <p class="font-medium text-gray-700">{{ $prospect->nearby_screen }}</p>
                </div>
                @endif
                @if($prospect->next_followup_date)
                <div>
                    <span class="text-gray-400">Próximo seguimiento</span>
                    @php $isOverdue = $prospect->next_followup_date->isPast(); @endphp
                    <p class="font-medium {{ $isOverdue ? 'text-red-600' : 'text-gray-700' }}">
                        {{ $prospect->next_followup_date->format('d/m/Y') }}
                        @if($isOverdue)
                            <span class="text-xs">(vencido)</span>
                        @endif
                    </p>
                </div>
                @endif
                @if($prospect->data_source)
                <div>
                    <span class="text-gray-400">Fuente de datos</span>
                    <p class="font-medium text-gray-700">{{ $prospect->data_source }}</p>
                </div>
                @endif
            </div>

            @if($prospect->notes)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <span class="text-gray-400 text-sm">Notas</span>
                <p class="text-sm text-gray-700 mt-1">{{ $prospect->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Pitch advisor card --}}
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl shadow-sm border border-amber-200/60 p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-amber-600" style="font-size:24px">tips_and_updates</span>
                <h3 class="text-lg font-bold text-amber-800">Consejos de Pitch — {{ $prospect->category ?? 'General' }}</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">Consejo clave</p>
                    <p class="text-sm text-amber-900">{{ $pitchAdvice['tip'] }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">Cómo abordar</p>
                    <p class="text-sm text-amber-900">{{ $pitchAdvice['approach'] }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">Frase de apertura</p>
                    <p class="text-sm text-amber-900 italic">"{{ $pitchAdvice['opening'] }}"</p>
                </div>

                @if(!empty($pitchAdvice['objections']))
                <div>
                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-2">Manejo de objeciones</p>
                    <div class="space-y-2">
                        @foreach($pitchAdvice['objections'] as $objection => $response)
                        <div class="bg-white/60 rounded-xl p-3">
                            <p class="text-xs font-semibold text-red-600 mb-1">
                                <span class="material-symbols-outlined align-middle" style="font-size:14px">record_voice_over</span>
                                "{{ $objection }}"
                            </p>
                            <p class="text-xs text-amber-900">
                                <span class="material-symbols-outlined align-middle text-emerald-600" style="font-size:14px">support_agent</span>
                                {{ $response }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Interaction timeline --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:22px">history</span>
                Historial de interacciones
            </h3>

            @if($prospect->interactions->count())
            <div class="space-y-4">
                @foreach($prospect->interactions as $interaction)
                <div class="relative pl-8 pb-4 {{ !$loop->last ? 'border-l-2 border-gray-100 ml-3' : 'ml-3' }}">
                    @php
                        $typeIcons = [
                            'llamada' => ['icon' => 'call', 'color' => 'bg-blue-100 text-blue-600'],
                            'visita' => ['icon' => 'location_on', 'color' => 'bg-emerald-100 text-emerald-600'],
                            'whatsapp' => ['icon' => 'chat', 'color' => 'bg-green-100 text-green-600'],
                            'email' => ['icon' => 'email', 'color' => 'bg-purple-100 text-purple-600'],
                            'otro' => ['icon' => 'more_horiz', 'color' => 'bg-gray-100 text-gray-600'],
                        ];
                        $typeStyle = $typeIcons[$interaction->type] ?? $typeIcons['otro'];
                    @endphp
                    <div class="absolute -left-3 top-0 w-6 h-6 rounded-full {{ $typeStyle['color'] }} flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size:14px">{{ $typeStyle['icon'] }}</span>
                    </div>
                    <div class="bg-gray-50/50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-800">{{ \App\Models\ProspectInteraction::TYPES[$interaction->type] ?? $interaction->type }}</span>
                                @if($interaction->result)
                                    <span class="text-xs text-gray-500">— {{ $interaction->result }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-400">{{ $interaction->interaction_date->format('d/m/Y H:i') }}</span>
                                <form method="POST" action="{{ route('prospects.interactions.destroy', [$prospect, $interaction]) }}" onsubmit="return confirm('¿Eliminar esta interacción?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-500 transition">
                                        <span class="material-symbols-outlined" style="font-size:16px">close</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if($interaction->notes)
                        <p class="text-sm text-gray-600 mt-1">{{ $interaction->notes }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                            <span>por {{ $interaction->user->name ?? 'Sistema' }}</span>
                            @if($interaction->next_followup_date)
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined" style="font-size:12px">event</span>
                                    Seguimiento: {{ $interaction->next_followup_date->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <span class="material-symbols-outlined text-gray-300" style="font-size:40px">forum</span>
                <p class="text-sm text-gray-400 mt-2">Sin interacciones registradas</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Right column: Quick actions + New interaction form --}}
    <div class="space-y-6">
        {{-- Quick status change --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px">swap_horiz</span>
                Cambiar estado
            </h4>
            <div class="grid grid-cols-2 gap-2">
                @foreach(\App\Models\Prospect::STATUSES as $key => $label)
                    @if($key !== $prospect->status)
                    <form method="POST" action="{{ route('prospects.update', $prospect) }}" class="inline">
                        @csrf @method('PUT')
                        <input type="hidden" name="business_name" value="{{ $prospect->business_name }}">
                        <input type="hidden" name="status" value="{{ $key }}">
                        @php
                            $btnColors = [
                                'por_visitar' => 'hover:bg-slate-100 text-slate-600',
                                'contactado' => 'hover:bg-blue-100 text-blue-600',
                                'interesado' => 'hover:bg-amber-100 text-amber-600',
                                'negociando' => 'hover:bg-purple-100 text-purple-600',
                                'firmado' => 'hover:bg-emerald-100 text-emerald-600',
                                'no_interesado' => 'hover:bg-red-100 text-red-600',
                            ];
                        @endphp
                        <button type="submit" class="w-full text-left px-3 py-2 text-xs font-medium rounded-lg border border-gray-100 {{ $btnColors[$key] ?? '' }} transition">
                            {{ $label }}
                        </button>
                    </form>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- New interaction form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px">add_comment</span>
                Registrar interacción
            </h4>
            <form method="POST" action="{{ route('prospects.interactions.store', $prospect) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tipo</label>
                    <select name="type" required class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none">
                        @foreach(\App\Models\ProspectInteraction::TYPES as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Fecha</label>
                    <input type="datetime-local" name="interaction_date" value="{{ now()->format('Y-m-d\TH:i') }}" required
                           class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Resultado</label>
                    <input type="text" name="result" placeholder="Ej: Interesado, volverá a llamar..."
                           class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Notas</label>
                    <textarea name="notes" rows="3" placeholder="Detalles de la interacción..."
                              class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Próximo seguimiento</label>
                    <input type="date" name="next_followup_date"
                           class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Cambiar estado a</label>
                    <select name="new_status" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="">— Sin cambio —</option>
                        @foreach(\App\Models\Prospect::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ $prospect->status === $key ? 'disabled' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-brand-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:18px">save</span>
                    Registrar
                </button>
            </form>
        </div>

        {{-- Delete --}}
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
            <form method="POST" action="{{ route('prospects.destroy', $prospect) }}" onsubmit="return confirm('¿Está seguro de eliminar este prospecto? Esta acción no se puede deshacer.')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full text-red-600 hover:bg-red-50 py-2.5 rounded-xl text-sm font-medium transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:18px">delete</span>
                    Eliminar prospecto
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
