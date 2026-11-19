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
                <div class="flex items-center gap-2">
                    @if(!$prospect->isConverted())
                        <a href="{{ route('prospects.convert', $prospect) }}" class="inline-flex items-center gap-1 text-sm text-white bg-emerald-600 px-3 py-1.5 rounded-lg hover:bg-emerald-700 transition shadow-sm font-medium">
                            <span class="material-symbols-outlined" style="font-size:16px">person_add</span> Convertir a Cliente
                        </a>
                    @else
                        <a href="{{ route('clients.show', $prospect->converted_to_client_id) }}" class="inline-flex items-center gap-1 text-sm text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200 hover:bg-emerald-100 transition font-medium">
                            <span class="material-symbols-outlined" style="font-size:16px">check_circle</span> Ver Cliente
                        </a>
                    @endif
                    <a href="{{ route('prospects.edit', $prospect) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-600 border border-gray-200 px-3 py-1.5 rounded-lg hover:border-brand-300 transition">
                        <span class="material-symbols-outlined" style="font-size:16px">edit</span> Editar
                    </a>
                </div>
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

        {{-- Deal info card --}}
        @if($prospect->deal_amount || $prospect->isConverted())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-purple-600" style="font-size:22px">payments</span>
                Negociación
                @if($prospect->isConverted())
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 ml-2">
                        <span class="material-symbols-outlined" style="font-size:14px">check</span> Convertido
                    </span>
                @endif
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                @if($prospect->deal_amount)
                <div>
                    <span class="text-gray-400">Valor</span>
                    <p class="font-bold text-gray-800 text-lg">L. {{ number_format($prospect->deal_amount, 2) }}</p>
                </div>
                @endif
                @if($prospect->payment_frequency)
                <div>
                    <span class="text-gray-400">Frecuencia de pago</span>
                    <p class="font-medium text-gray-700">{{ \App\Models\Prospect::PAYMENT_FREQUENCIES[$prospect->payment_frequency] ?? $prospect->payment_frequency }}</p>
                </div>
                @endif
                @if($prospect->payment_start_date)
                <div>
                    <span class="text-gray-400">Inicio de pagos</span>
                    <p class="font-medium text-gray-700">{{ $prospect->payment_start_date->format('d/m/Y') }}</p>
                </div>
                @endif
                @if($prospect->payment_end_date)
                <div>
                    <span class="text-gray-400">Fin de contrato</span>
                    <p class="font-medium text-gray-700">{{ $prospect->payment_end_date->format('d/m/Y') }}</p>
                </div>
                @endif
                @if($prospect->converted_at)
                <div>
                    <span class="text-gray-400">Fecha de conversión</span>
                    <p class="font-medium text-gray-700">{{ $prospect->converted_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
                @if($prospect->isConverted())
                <div>
                    <span class="text-gray-400">Cliente</span>
                    <p class="font-medium">
                        <a href="{{ route('clients.show', $prospect->converted_to_client_id) }}" class="text-brand-600 hover:text-brand-700">
                            Ver ficha del cliente →
                        </a>
                    </p>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Video / Ad card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500" style="font-size:22px">videocam</span>
                Anuncio / Video
            </h3>

            @if($prospect->ad_video_path)
                <div class="mb-4">
                    <video controls class="w-full rounded-xl border border-gray-200 max-h-64">
                        <source src="{{ asset('storage/' . $prospect->ad_video_path) }}" type="video/mp4">
                        Tu navegador no soporta la reproducción de video.
                    </video>
                    <div class="flex items-center justify-between mt-2">
                        <p class="text-xs text-gray-500">{{ $prospect->ad_video_original_name ?? 'Video' }}</p>
                        <form method="POST" action="{{ route('prospects.delete-video', $prospect) }}" onsubmit="return confirm('¿Eliminar este video?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1">
                                <span class="material-symbols-outlined" style="font-size:14px">delete</span> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="text-center py-6 mb-4">
                    <span class="material-symbols-outlined text-gray-300" style="font-size:40px">video_file</span>
                    <p class="text-sm text-gray-400 mt-2">Sin video cargado</p>
                </div>
            @endif

            <form method="POST" action="{{ route('prospects.upload-video', $prospect) }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="file" name="ad_video" accept="video/mp4,video/avi,video/mov,video/wmv,video/webm" required
                       class="w-full border border-gray-200 rounded-xl text-sm py-2 px-4 focus:ring-2 focus:ring-brand-500 outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                <p class="text-xs text-gray-400">MP4, AVI, MOV, WMV, WebM. Máx. 100MB.</p>
                @error('ad_video')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
                <button type="submit" class="w-full bg-gray-800 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-900 transition shadow-sm flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:18px">upload</span>
                    Subir Video
                </button>
            </form>
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

        {{-- WhatsApp messaging --}}
        @if($prospect->phone && count($whatsappTemplates))
        <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-6" x-data="{
            showCustom: false,
            customMsg: '',
            phone: '504{{ preg_replace('/\D/', '', $prospect->phone) }}',
            templates: {{ Js::from($whatsappTemplates) }},
            selectedTemplate: '',
            sendTemplate(key) {
                const msg = this.templates[key];
                window.open('https://wa.me/' + this.phone + '?text=' + encodeURIComponent(msg), '_blank');
            },
            sendCustom() {
                if (this.customMsg.trim()) {
                    window.open('https://wa.me/' + this.phone + '?text=' + encodeURIComponent(this.customMsg), '_blank');
                }
            },
            previewTemplate(key) {
                this.selectedTemplate = key;
                this.customMsg = this.templates[key];
                this.showCustom = true;
            }
        }">
            <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-green-600" style="font-size:18px">chat</span>
                Enviar WhatsApp
            </h4>

            {{-- Promo images to send --}}
            <div class="mb-4 p-3 bg-green-50/50 rounded-xl border border-green-100">
                <p class="text-xs text-gray-500 mb-2 flex items-center gap-1">
                    <span class="material-symbols-outlined" style="font-size:14px">info</span>
                    Envía primero las imágenes y luego el mensaje de texto
                </p>
                <div class="grid grid-cols-3 gap-2 mb-2">
                    @foreach(['publiktec-promo.jpg', 'publiktec-promo-1.jpg', 'publiktec-promo-2.jpg'] as $img)
                    <a href="{{ asset('storage/promo/' . $img) }}" download="{{ $img }}" class="block group relative">
                        <img src="{{ asset('storage/promo/' . $img) }}" class="w-full aspect-square object-cover rounded-lg border border-green-200 group-hover:border-green-400 transition" alt="Promo">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 rounded-lg flex items-center justify-center transition">
                            <span class="material-symbols-outlined text-white opacity-0 group-hover:opacity-100 transition" style="font-size:20px">download</span>
                        </div>
                    </a>
                    @endforeach
                </div>
                <a href="#" onclick="document.querySelectorAll('.promo-download').forEach(a => a.click()); return false;"
                   class="w-full text-center px-3 py-2.5 text-xs font-semibold rounded-xl bg-green-600 text-white hover:bg-green-700 transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:16px">download</span>
                    Descargar todas las imágenes
                </a>
                @foreach(['publiktec-promo.jpg', 'publiktec-promo-1.jpg', 'publiktec-promo-2.jpg'] as $img)
                <a href="{{ asset('storage/promo/' . $img) }}" download="{{ $img }}" class="promo-download hidden"></a>
                @endforeach
            </div>

            {{-- Template buttons --}}
            <div class="space-y-2 mb-4">
                <button @click="sendTemplate('introduccion')" class="w-full text-left px-3 py-2.5 text-xs font-medium rounded-xl border border-green-100 hover:bg-green-50 text-green-700 transition flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:16px">waving_hand</span>
                    Mensaje de introducción
                </button>
                <button @click="sendTemplate('seguimiento')" class="w-full text-left px-3 py-2.5 text-xs font-medium rounded-xl border border-green-100 hover:bg-green-50 text-green-700 transition flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:16px">replay</span>
                    Seguimiento
                </button>
                <button @click="sendTemplate('propuesta')" class="w-full text-left px-3 py-2.5 text-xs font-medium rounded-xl border border-green-100 hover:bg-green-50 text-green-700 transition flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:16px">description</span>
                    Propuesta de servicios
                </button>
                <button @click="sendTemplate('promocion')" class="w-full text-left px-3 py-2.5 text-xs font-medium rounded-xl border border-green-100 hover:bg-green-50 text-green-700 transition flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:16px">local_offer</span>
                    Promoción especial
                </button>
                <button @click="sendTemplate('recordatorio')" class="w-full text-left px-3 py-2.5 text-xs font-medium rounded-xl border border-green-100 hover:bg-green-50 text-green-700 transition flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:16px">notifications</span>
                    Recordatorio
                </button>
            </div>

            {{-- Preview / Edit before sending --}}
            <div class="border-t border-gray-100 pt-3">
                <button @click="showCustom = !showCustom; if(!customMsg) customMsg = templates['introduccion']" class="text-xs text-gray-500 hover:text-brand-600 flex items-center gap-1 mb-2">
                    <span class="material-symbols-outlined" style="font-size:14px" x-text="showCustom ? 'expand_less' : 'edit_note'"></span>
                    <span x-text="showCustom ? 'Ocultar editor' : 'Personalizar mensaje'"></span>
                </button>
                <div x-show="showCustom" x-transition class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Usar plantilla como base</label>
                        <select @change="if($event.target.value) customMsg = templates[$event.target.value]" class="w-full border border-gray-200 rounded-xl text-xs py-2 px-3 focus:ring-2 focus:ring-green-500 outline-none">
                            <option value="">— Elegir plantilla —</option>
                            <option value="introduccion">Introducción</option>
                            <option value="seguimiento">Seguimiento</option>
                            <option value="propuesta">Propuesta</option>
                            <option value="promocion">Promoción</option>
                            <option value="recordatorio">Recordatorio</option>
                        </select>
                    </div>
                    <textarea x-model="customMsg" rows="6"
                              class="w-full border border-gray-200 rounded-xl text-xs py-2.5 px-3 focus:ring-2 focus:ring-green-500 outline-none resize-none leading-relaxed"></textarea>
                    <button @click="sendCustom()" class="w-full bg-green-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700 transition shadow-sm flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined" style="font-size:18px">send</span>
                        Enviar por WhatsApp
                    </button>
                </div>
            </div>
        </div>
        @elseif(!$prospect->phone)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <span class="material-symbols-outlined text-gray-300" style="font-size:32px">chat</span>
            <p class="text-xs text-gray-400 mt-2">Agrega un teléfono para habilitar WhatsApp</p>
            <a href="{{ route('prospects.edit', $prospect) }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium">Editar prospecto</a>
        </div>
        @endif

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

        {{-- Convert to client --}}
        @if(!$prospect->isConverted())
        <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl shadow-sm border border-emerald-200/60 p-6">
            <h4 class="text-sm font-bold text-emerald-700 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px">trending_up</span>
                Convertir a Cliente
            </h4>
            <p class="text-xs text-emerald-600 mb-4">Convierte este prospecto en cliente, define el valor de la negociación y genera el contrato automáticamente.</p>
            <a href="{{ route('prospects.convert', $prospect) }}" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition shadow-sm">
                <span class="material-symbols-outlined" style="font-size:18px">person_add</span>
                Convertir a Cliente
            </a>
        </div>
        @else
        <div class="bg-emerald-50 rounded-2xl shadow-sm border border-emerald-200/60 p-6">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-emerald-600" style="font-size:20px">check_circle</span>
                <h4 class="text-sm font-bold text-emerald-700">Cliente convertido</h4>
            </div>
            <p class="text-xs text-emerald-600 mb-3">Convertido el {{ $prospect->converted_at->format('d/m/Y') }}</p>
            <a href="{{ route('clients.show', $prospect->converted_to_client_id) }}" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition shadow-sm">
                <span class="material-symbols-outlined" style="font-size:18px">visibility</span>
                Ver ficha del Cliente
            </a>
        </div>
        @endif

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
