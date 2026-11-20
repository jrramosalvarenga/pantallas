@extends('layouts.app')

@section('title', 'Editar Pago')
@section('page-title', 'Editar Pago')
@section('page-subtitle', $payment->client?->business_name ?? 'Pago')

@section('content')
<div class="max-w-2xl">
    <div class="mb-4">
        <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-600">
            <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Volver a pagos
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-size:22px">edit_calendar</span>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-800">{{ $payment->client?->business_name ?? '—' }}</h3>
                <p class="text-xs text-gray-400">Contrato {{ $payment->contract?->contract_number ?? '—' }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('payments.update', $payment) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de vencimiento *</label>
                <input type="date" name="due_date" value="{{ old('due_date', $payment->due_date->format('Y-m-d')) }}" required
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                @error('due_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Monto (L.) *</label>
                <input type="number" name="amount" value="{{ old('amount', $payment->amount) }}" step="0.01" min="0" required
                       class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                @error('amount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <textarea name="notes" rows="2" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('notes', $payment->notes) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-600 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm">
                    <span class="material-symbols-outlined" style="font-size:18px">save</span> Guardar Cambios
                </button>
                <a href="{{ route('payments.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
