@extends('layouts.app')

@section('title', 'Egresos')
@section('page-title', 'Egresos')
@section('page-subtitle', 'Control de gastos operativos')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <select name="category" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-brand-500 outline-none" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
    <a href="{{ route('expenses.create') }}" class="inline-flex items-center gap-2 bg-brand-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm">
        <span class="material-symbols-outlined" style="font-size:18px">add</span>
        Nuevo egreso
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Fecha</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Descripción</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Categoría</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Pantalla</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Recurrencia</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Monto</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($expenses as $expense)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3 text-gray-600">{{ $expense->date->format('d/m/Y') }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $expense->description }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-100 text-gray-600">{{ $expense->category->name ?? '—' }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $expense->screen->name ?? 'General' }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-medium px-2 py-1 rounded-full
                            {{ $expense->recurrence === 'mensual' ? 'bg-blue-50 text-blue-600' : ($expense->recurrence === 'unico' ? 'bg-gray-100 text-gray-500' : 'bg-purple-50 text-purple-600') }}">
                            {{ ucfirst($expense->recurrence) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right font-semibold text-gray-800">L {{ number_format($expense->amount, 2) }}</td>
                    <td class="px-5 py-3">
                        <div class="flex justify-end gap-1">
                            <a href="{{ route('expenses.edit', $expense) }}" class="text-gray-400 hover:text-brand-600 p-1">
                                <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                            </a>
                            <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('¿Eliminar este egreso?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-gray-400 hover:text-red-600 p-1"><span class="material-symbols-outlined" style="font-size:18px">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <span class="material-symbols-outlined text-gray-300" style="font-size:48px">trending_down</span>
                        <p class="mt-2">No hay egresos registrados</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($expenses->count())
            <tfoot>
                <tr class="border-t-2 border-gray-200 bg-gray-50/50">
                    <td colspan="5" class="px-5 py-3 text-sm font-semibold text-gray-600">Total</td>
                    <td class="px-5 py-3 text-right text-sm font-bold text-gray-800">L {{ number_format($totalFiltered, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

<div class="mt-6">{{ $expenses->withQueryString()->links() }}</div>
@endsection
