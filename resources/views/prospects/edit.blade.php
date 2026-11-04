@extends('layouts.app')

@section('title', 'Editar Prospecto')
@section('page-title', 'Editar Prospecto')
@section('page-subtitle', $prospect->business_name)

@section('content')
<div class="mb-4">
    <a href="{{ route('prospects.show', $prospect) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-600">
        <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Volver al prospecto
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-3xl">
    <form method="POST" action="{{ route('prospects.update', $prospect) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del negocio *</label>
                <input type="text" name="business_name" value="{{ old('business_name', $prospect->business_name) }}" required
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                <input type="text" name="category" value="{{ old('category', $prospect->category) }}"
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del contacto</label>
                <input type="text" name="contact_name" value="{{ old('contact_name', $prospect->contact_name) }}"
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone', $prospect->phone) }}"
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
                <select name="municipality_id" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                    <option value="">— Seleccionar —</option>
                    @foreach($municipalities as $mun)
                        <option value="{{ $mun->id }}" {{ old('municipality_id', $prospect->municipality_id) == $mun->id ? 'selected' : '' }}>{{ $mun->name }} ({{ $mun->department }})</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <input type="text" name="address" value="{{ old('address', $prospect->address) }}"
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Origen / Zona</label>
                <input type="text" name="origin" value="{{ old('origin', $prospect->origin) }}"
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fuente de datos</label>
                <input type="text" name="data_source" value="{{ old('data_source', $prospect->data_source) }}"
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pantalla cercana</label>
                <input type="text" name="nearby_screen" value="{{ old('nearby_screen', $prospect->nearby_screen) }}"
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                <select name="priority" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                    <option value="">— Sin asignar —</option>
                    @foreach(\App\Models\Prospect::PRIORITIES as $key => $label)
                        <option value="{{ $key }}" {{ old('priority', $prospect->priority) === $key ? 'selected' : '' }}>{{ $key }} — {{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                <select name="status" required class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                    @foreach(\App\Models\Prospect::STATUSES as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $prospect->status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Plan de interés</label>
                <input type="text" name="plan_of_interest" value="{{ old('plan_of_interest', $prospect->plan_of_interest) }}"
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de visita</label>
                <input type="date" name="visit_date" value="{{ old('visit_date', $prospect->visit_date?->format('Y-m-d')) }}"
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Próximo seguimiento</label>
                <input type="date" name="next_followup_date" value="{{ old('next_followup_date', $prospect->next_followup_date?->format('Y-m-d')) }}"
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                @if($prospect->logo_path)
                    <div class="flex items-center gap-3 mb-2">
                        <img src="{{ asset('storage/' . $prospect->logo_path) }}" class="w-10 h-10 rounded-lg object-cover" alt="">
                        <span class="text-xs text-gray-400">Logo actual</span>
                    </div>
                @endif
                <input type="file" name="logo" accept="image/*"
                       class="w-full border border-gray-200 rounded-xl text-sm py-2 px-4 focus:ring-2 focus:ring-brand-500 outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <textarea name="notes" rows="3" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none resize-none">{{ old('notes', $prospect->notes) }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 bg-brand-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm">
                <span class="material-symbols-outlined" style="font-size:18px">save</span>
                Actualizar
            </button>
            <a href="{{ route('prospects.show', $prospect) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
        </div>
    </form>
</div>
@endsection
