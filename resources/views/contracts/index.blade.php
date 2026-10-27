@extends('layouts.app')

@section('title', 'Contratos')
@section('page-title', 'Contratos')
@section('page-subtitle', 'Gestión de contratos publicitarios')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <form method="GET" class="flex flex-wrap items-center gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar cliente..."
               class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none w-56">
        <select name="status" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">Todos los estados</option>
            <option value="activo" {{ request('status') === 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="vencido" {{ request('status') === 'vencido' ? 'selected' : '' }}>Vencido</option>
            <option value="cancelado" {{ request('status') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            <option value="suspendido" {{ request('status') === 'suspendido' ? 'selected' : '' }}>Suspendido</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm hover:bg-gray-200 transition">Filtrar</button>
    </form>
    <a href="{{ route('contracts.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white rounded-xl text-sm font-medium hover:bg-brand-700 transition shadow-sm">
        <span class="material-symbols-outlined" style="font-size:18px">add</span>
        Nuevo Contrato
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Nº Contrato</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Cliente</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Plan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Periodo</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Monto/Mes</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Estado</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($contracts as $contract)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $contract->contract_number }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $contract->client->business_name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $contract->plan->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">
                        {{ $contract->start_date->format('d/m/Y') }} — {{ $contract->end_date->format('d/m/Y') }}
                    </td>
                    <td class="px-5 py-3 text-right font-semibold text-gray-800">L {{ number_format($contract->monthly_amount, 2) }}</td>
                    <td class="px-5 py-3 text-center">
                        @php
                        $colors = ['activo' => 'emerald', 'vencido' => 'red', 'cancelado' => 'gray', 'suspendido' => 'amber'];
                        $c = $colors[$contract->status] ?? 'gray';
                        @endphp
                        <span class="inline-block text-[10px] font-semibold px-2.5 py-1 rounded-full bg-{{ $c }}-50 text-{{ $c }}-600">
                            {{ ucfirst($contract->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('contracts.show', $contract) }}" class="p-1.5 text-gray-400 hover:text-brand-600 rounded-lg hover:bg-brand-50 transition" title="Ver">
                                <span class="material-symbols-outlined" style="font-size:18px">visibility</span>
                            </a>
                            <a href="{{ route('contracts.edit', $contract) }}" class="p-1.5 text-gray-400 hover:text-amber-600 rounded-lg hover:bg-amber-50 transition" title="Editar">
                                <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center">
                        <span class="material-symbols-outlined text-gray-300" style="font-size:48px">description</span>
                        <p class="text-gray-400 mt-2">No hay contratos registrados</p>
                        <a href="{{ route('contracts.create') }}" class="inline-flex items-center gap-1 text-brand-600 text-sm font-medium mt-2 hover:underline">
                            <span class="material-symbols-outlined" style="font-size:16px">add</span> Crear primer contrato
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($contracts->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $contracts->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
