@extends('layouts.app')

@section('title', 'Editar Contrato')
@section('page-title', 'Editar Contrato')
@section('page-subtitle', $contract->contract_number)

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('contracts.update', $contract) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-500" style="font-size:20px">edit</span>
                Datos del Contrato — {{ $contract->contract_number }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cliente *</label>
                    <select name="client_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $contract->client_id == $client->id ? 'selected' : '' }}>{{ $client->business_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Plan *</label>
                    <select name="plan_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ $contract->plan_id == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} — L {{ number_format($plan->final_price, 2) }}/mes
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha de inicio *</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha de fin *</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $contract->end_date->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Frecuencia de pago</label>
                    <select name="payment_frequency" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        @foreach(['mensual', 'trimestral', 'semestral', 'anual'] as $freq)
                        <option value="{{ $freq }}" {{ $contract->payment_frequency === $freq ? 'selected' : '' }}>{{ ucfirst($freq) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Estado</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        @foreach(['activo', 'vencido', 'cancelado', 'suspendido'] as $st)
                        <option value="{{ $st }}" {{ $contract->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Notas</label>
                <textarea name="notes" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('notes', $contract->notes) }}</textarea>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-500" style="font-size:20px">tv</span>
                Pantallas y Espacios
            </h3>
            @php $attached = $contract->screens->pluck('pivot.space_number', 'id')->toArray(); @endphp
            <div class="space-y-3">
                @foreach($screens as $screen)
                <label class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:border-brand-300 transition cursor-pointer">
                    <input type="checkbox" name="screens[{{ $loop->index }}][id]" value="{{ $screen->id }}"
                           {{ isset($attached[$screen->id]) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700">{{ $screen->name }}</p>
                        <p class="text-xs text-gray-400">{{ $screen->municipality->name ?? '' }}</p>
                    </div>
                    <div class="w-24">
                        <label class="text-[10px] text-gray-500">Espacio Nº</label>
                        <input type="number" name="screens[{{ $loop->index }}][space_number]" min="1" max="{{ $screen->total_spaces }}"
                               value="{{ $attached[$screen->id] ?? 1 }}"
                               class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white rounded-xl text-sm font-medium hover:bg-brand-700 transition shadow-sm">
                Guardar Cambios
            </button>
            <a href="{{ route('contracts.show', $contract) }}" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
