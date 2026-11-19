@extends('layouts.app')

@section('title', 'Convertir a Cliente')
@section('page-title', 'Convertir a Cliente')
@section('page-subtitle', $prospect->business_name)

@section('content')
<div class="mb-4">
    <a href="{{ route('prospects.show', $prospect) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-600">
        <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Volver al prospecto
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main form --}}
    <div class="lg:col-span-2">
        <form method="POST" action="{{ route('prospects.convert.store', $prospect) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Prospect summary --}}
            <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl shadow-sm border border-emerald-200/60 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size:24px">person_add</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-emerald-800">{{ $prospect->business_name }}</h3>
                        <p class="text-sm text-emerald-600">{{ $prospect->contact_name ?? 'Sin contacto' }} &middot; {{ $prospect->phone ?? 'Sin teléfono' }}</p>
                    </div>
                </div>
                <p class="text-sm text-emerald-700">
                    Este prospecto será convertido a cliente. Se creará un registro de cliente con los datos existentes y podrás definir los términos de la negociación.
                </p>
            </div>

            {{-- Deal details --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600" style="font-size:20px">payments</span>
                    Detalles de la Negociación
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valor de la negociación (L.) *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">L.</span>
                            <input type="number" name="deal_amount" value="{{ old('deal_amount', $prospect->deal_amount) }}" required step="0.01" min="0"
                                   class="w-full border border-gray-200 rounded-xl text-sm py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
                                   placeholder="0.00">
                        </div>
                        @error('deal_amount')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Frecuencia de pago *</label>
                        <select name="payment_frequency" required class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                            <option value="">— Seleccionar —</option>
                            @foreach(\App\Models\Prospect::PAYMENT_FREQUENCIES as $key => $label)
                                <option value="{{ $key }}" {{ old('payment_frequency', $prospect->payment_frequency) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('payment_frequency')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Plan</label>
                        <select name="plan_id" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                            <option value="">— Sin plan específico —</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} — L. {{ number_format($plan->final_price, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha inicio de pagos *</label>
                        <input type="date" name="payment_start_date" value="{{ old('payment_start_date', now()->format('Y-m-d')) }}" required
                               class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
                        @error('payment_start_date')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha fin de contrato *</label>
                        <input type="date" name="payment_end_date" value="{{ old('payment_end_date', now()->addMonths(6)->format('Y-m-d')) }}" required
                               class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
                        @error('payment_end_date')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pantalla</label>
                        <select name="screen_id" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                            <option value="">— Seleccionar pantalla —</option>
                            @foreach($screens as $screen)
                                <option value="{{ $screen->id }}" {{ old('screen_id') == $screen->id ? 'selected' : '' }}>
                                    {{ $screen->name }} ({{ $screen->available_spaces }} espacios libres)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de espacio</label>
                        <input type="number" name="space_number" value="{{ old('space_number', 1) }}" min="1"
                               class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
                    </div>
                </div>
            </div>

            {{-- Client extra info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600" style="font-size:20px">badge</span>
                    Datos adicionales del Cliente
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com"
                               class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RTN</label>
                        <input type="text" name="rtn" value="{{ old('rtn') }}" placeholder="0000-0000-000000"
                               class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                        <textarea name="notes" rows="3" placeholder="Notas adicionales sobre el cliente..."
                                  class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none resize-none">{{ old('notes', $prospect->notes) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Video upload --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-red-500" style="font-size:20px">videocam</span>
                    Anuncio / Video
                </h4>

                @if($prospect->ad_video_path)
                    <div class="mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-emerald-600" style="font-size:28px">check_circle</span>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Video ya cargado</p>
                                <p class="text-xs text-gray-500">{{ $prospect->ad_video_original_name ?? 'Video del prospecto' }}</p>
                            </div>
                        </div>
                        <video controls class="w-full mt-3 rounded-lg max-h-48">
                            <source src="{{ asset('storage/' . $prospect->ad_video_path) }}" type="video/mp4">
                        </video>
                    </div>
                    <p class="text-xs text-gray-500 mb-2">Puedes subir un video nuevo para reemplazar el existente:</p>
                @endif

                <input type="file" name="ad_video" accept="video/mp4,video/avi,video/mov,video/wmv,video/webm"
                       class="w-full border border-gray-200 rounded-xl text-sm py-2 px-4 focus:ring-2 focus:ring-brand-500 outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                <p class="text-xs text-gray-400 mt-1">Formatos: MP4, AVI, MOV, WMV, WebM. Máximo 100MB.</p>
                @error('ad_video')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition shadow-sm">
                    <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
                    Convertir a Cliente
                </button>
                <a href="{{ route('prospects.show', $prospect) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
            </div>
        </form>
    </div>

    {{-- Right sidebar: prospect summary --}}
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px">info</span>
                Datos del Prospecto
            </h4>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-gray-400">Empresa</span>
                    <p class="font-medium text-gray-700">{{ $prospect->business_name }}</p>
                </div>
                @if($prospect->contact_name)
                <div>
                    <span class="text-gray-400">Contacto</span>
                    <p class="font-medium text-gray-700">{{ $prospect->contact_name }}</p>
                </div>
                @endif
                @if($prospect->phone)
                <div>
                    <span class="text-gray-400">Teléfono</span>
                    <p class="font-medium text-gray-700">{{ $prospect->phone }}</p>
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
                @if($prospect->category)
                <div>
                    <span class="text-gray-400">Categoría</span>
                    <p class="font-medium text-gray-700">{{ $prospect->category }}</p>
                </div>
                @endif
                @if($prospect->plan_of_interest)
                <div>
                    <span class="text-gray-400">Plan de interés</span>
                    <p class="font-medium text-gray-700">{{ $prospect->plan_of_interest }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-amber-50 rounded-2xl shadow-sm border border-amber-200/60 p-6">
            <h4 class="text-sm font-bold text-amber-700 mb-2 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px">lightbulb</span>
                Al convertir
            </h4>
            <ul class="text-xs text-amber-800 space-y-2">
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-amber-600" style="font-size:14px; margin-top:2px">check</span>
                    Se creará un registro de <strong>Cliente</strong> con los datos del prospecto
                </li>
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-amber-600" style="font-size:14px; margin-top:2px">check</span>
                    Se generará un <strong>Contrato</strong> si seleccionas un plan
                </li>
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-amber-600" style="font-size:14px; margin-top:2px">check</span>
                    Se crearán los <strong>pagos programados</strong> automáticamente
                </li>
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-amber-600" style="font-size:14px; margin-top:2px">check</span>
                    El video se vinculará como <strong>Media</strong> del cliente
                </li>
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-amber-600" style="font-size:14px; margin-top:2px">check</span>
                    El prospecto quedará marcado como <strong>Firmado</strong>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
