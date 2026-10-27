@extends('layouts.app')

@section('title', 'Ingresos')
@section('page-title', 'Ingresos')
@section('page-subtitle', 'Control de ingresos por publicidad')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <select name="client_id" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-brand-500 outline-none" onchange="this.form.submit()">
            <option value="">Todos los clientes</option>
            @foreach($clients as $client)
            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->business_name }}</option>
            @endforeach
        </select>
        <select name="month" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-brand-500 outline-none" onchange="this.form.submit()">
            <option value="">Todos los meses</option>
            @for($m = 1; $m <= 12; $m++)
            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
            @endfor
        </select>
        <select name="year" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-brand-500 outline-none" onchange="this.form.submit()">
            @for($y = now()->year; $y >= now()->year - 3; $y--)
            <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>
    <a href="{{ route('incomes.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition shadow-sm">
        <span class="material-symbols-outlined" style="font-size:18px">add</span>
        Nuevo ingreso
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Fecha</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Descripción</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Cliente</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Contrato</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Referencia</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Monto</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($incomes as $income)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3 text-gray-600">{{ $income->date->format('d/m/Y') }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $income->description }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $income->client->business_name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $income->contract->contract_number ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $income->reference ?? '—' }}</td>
                    <td class="px-5 py-3 text-right font-semibold text-emerald-600">L {{ number_format($income->amount, 2) }}</td>
                    <td class="px-5 py-3">
                        <div class="flex justify-end gap-1">
                            <a href="{{ route('incomes.edit', $income) }}" class="text-gray-400 hover:text-brand-600 p-1">
                                <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                            </a>
                            <form method="POST" action="{{ route('incomes.destroy', $income) }}" onsubmit="return confirm('¿Eliminar este ingreso?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-gray-400 hover:text-red-600 p-1"><span class="material-symbols-outlined" style="font-size:18px">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <span class="material-symbols-outlined text-gray-300" style="font-size:48px">trending_up</span>
                        <p class="mt-2">No hay ingresos registrados</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($incomes->count())
            <tfoot>
                <tr class="border-t-2 border-gray-200 bg-gray-50/50">
                    <td colspan="5" class="px-5 py-3 text-sm font-semibold text-gray-600">Total</td>
                    <td class="px-5 py-3 text-right text-sm font-bold text-emerald-600">L {{ number_format($totalFiltered, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

<div class="mt-6">{{ $incomes->withQueryString()->links() }}</div>
@endsection
