@extends('layouts.app')

@section('title', 'Planes')
@section('page-title', 'Planes Publicitarios')
@section('page-subtitle', 'Paquetes y precios de publicidad')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $plans->count() }} plan(es) configurados</p>
    <a href="{{ route('plans.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white rounded-xl text-sm font-medium hover:bg-brand-700 transition shadow-sm">
        <span class="material-symbols-outlined" style="font-size:18px">add</span>
        Nuevo Plan
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($plans as $plan)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
        <div class="p-6">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $plan->type === 'combo' ? 'bg-amber-100' : 'bg-brand-100' }}">
                        <span class="material-symbols-outlined {{ $plan->type === 'combo' ? 'text-amber-600' : 'text-brand-600' }}" style="font-size:20px">
                            {{ $plan->type === 'combo' ? 'join_full' : 'loyalty' }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">{{ $plan->name }}</h3>
                        <span class="text-[10px] font-semibold uppercase {{ $plan->type === 'combo' ? 'text-amber-600' : 'text-brand-500' }}">{{ $plan->type }}</span>
                    </div>
                </div>
                @if(!$plan->active)
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Inactivo</span>
                @endif
            </div>

            <div class="mt-4">
                <p class="text-3xl font-bold text-gray-800">L {{ number_format($plan->final_price, 2) }}</p>
                <p class="text-xs text-gray-400">por mes · {{ $plan->screens_count }} pantalla(s)</p>
            </div>

            <div class="mt-4 space-y-2 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:16px">category</span>
                    <span>{{ $plan->adType->name ?? '—' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:16px">calendar_month</span>
                    <span>{{ $plan->duration_months }} mes(es)</span>
                </div>
                @if($plan->discount_percent > 0)
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-500" style="font-size:16px">discount</span>
                    <span class="text-emerald-600 font-medium">{{ $plan->discount_percent }}% descuento</span>
                </div>
                @endif
            </div>

            @if($plan->description)
            <p class="mt-3 text-xs text-gray-400">{{ $plan->description }}</p>
            @endif
        </div>
        <div class="px-6 py-3 bg-gray-50/50 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-400">{{ $plan->contracts()->count() }} contrato(s)</span>
            <div class="flex items-center gap-1">
                <a href="{{ route('plans.edit', $plan) }}" class="p-1.5 text-gray-400 hover:text-amber-600 rounded-lg hover:bg-amber-50 transition" title="Editar">
                    <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                </a>
                <form method="POST" action="{{ route('plans.destroy', $plan) }}" onsubmit="return confirm('¿Eliminar este plan?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Eliminar">
                        <span class="material-symbols-outlined" style="font-size:18px">delete</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <span class="material-symbols-outlined text-gray-300" style="font-size:48px">loyalty</span>
        <p class="text-gray-400 mt-2">No hay planes configurados</p>
        <a href="{{ route('plans.create') }}" class="inline-flex items-center gap-1 text-brand-600 text-sm font-medium mt-2 hover:underline">
            <span class="material-symbols-outlined" style="font-size:16px">add</span> Crear primer plan
        </a>
    </div>
    @endforelse
</div>
@endsection
