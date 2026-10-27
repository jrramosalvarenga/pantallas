@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Clientes')
@section('page-subtitle', 'Gestión de clientes')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <form method="GET" class="flex items-center gap-2 flex-1 max-w-md">
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size:18px">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar cliente..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
        </div>
        <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">Todos</option>
            <option value="activo" {{ request('status') === 'activo' ? 'selected' : '' }}>Activos</option>
            <option value="inactivo" {{ request('status') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
            <option value="suspendido" {{ request('status') === 'suspendido' ? 'selected' : '' }}>Suspendidos</option>
        </select>
    </form>
    <a href="{{ route('clients.create') }}" class="inline-flex items-center gap-2 bg-brand-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm">
        <span class="material-symbols-outlined" style="font-size:18px">person_add</span>
        Nuevo Cliente
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/80 text-left">
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Empresa</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Contacto</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden md:table-cell">Teléfono</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden lg:table-cell">Municipio</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Estado</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($clients as $client)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-4">
                        <a href="{{ route('clients.show', $client) }}" class="font-medium text-gray-800 hover:text-brand-600">{{ $client->business_name }}</a>
                        @if($client->email)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $client->email }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-gray-600">{{ $client->contact_name }}</td>
                    <td class="px-5 py-4 text-gray-600 hidden md:table-cell">{{ $client->phone ?? '—' }}</td>
                    <td class="px-5 py-4 text-gray-600 hidden lg:table-cell">{{ $client->municipality->name ?? '—' }}</td>
                    <td class="px-5 py-4">
                        @php
                            $statusColors = [
                                'activo' => 'bg-emerald-50 text-emerald-700',
                                'inactivo' => 'bg-gray-100 text-gray-600',
                                'suspendido' => 'bg-red-50 text-red-600',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$client->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($client->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('clients.show', $client) }}" class="p-2 text-gray-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition" title="Ver">
                                <span class="material-symbols-outlined" style="font-size:18px">visibility</span>
                            </a>
                            <a href="{{ route('clients.edit', $client) }}" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Editar">
                                <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                            </a>
                            <form method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('¿Eliminar este cliente?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                                    <span class="material-symbols-outlined" style="font-size:18px">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <span class="material-symbols-outlined text-gray-300" style="font-size:48px">groups</span>
                        <p class="text-sm text-gray-400 mt-2">No se encontraron clientes</p>
                        <a href="{{ route('clients.create') }}" class="inline-flex items-center gap-1 text-sm text-brand-600 font-medium mt-2 hover:text-brand-700">
                            <span class="material-symbols-outlined" style="font-size:16px">add</span> Agregar el primero
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clients->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $clients->links() }}
    </div>
    @endif
</div>
@endsection
