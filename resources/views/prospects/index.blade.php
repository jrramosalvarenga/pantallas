@extends('layouts.app')

@section('title', 'Prospectos')
@section('page-title', 'Prospectos')
@section('page-subtitle', 'Pipeline de ventas')

@section('content')
{{-- Pipeline cards --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    @php
        $pipelineColors = [
            'por_visitar' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'icon' => 'location_on'],
            'contactado' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => 'phone_in_talk'],
            'interesado' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => 'thumb_up'],
            'negociando' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'icon' => 'handshake'],
            'firmado' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => 'check_circle'],
            'no_interesado' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => 'cancel'],
        ];
    @endphp
    @foreach(\App\Models\Prospect::STATUSES as $key => $label)
        @php $colors = $pipelineColors[$key]; @endphp
        <a href="{{ route('prospects.index', ['status' => $key]) }}"
           class="block {{ $colors['bg'] }} border {{ $colors['border'] }} rounded-2xl p-4 hover:shadow-md transition {{ request('status') === $key ? 'ring-2 ring-brand-500 shadow-md' : '' }}">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined {{ $colors['text'] }}" style="font-size:18px">{{ $colors['icon'] }}</span>
                <span class="text-2xl font-bold {{ $colors['text'] }}">{{ $statusCounts[$key] ?? 0 }}</span>
            </div>
            <p class="text-xs font-medium text-gray-500">{{ $label }}</p>
        </a>
    @endforeach
</div>

{{-- Filters --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <form method="GET" class="flex items-center gap-2 flex-1 max-w-2xl flex-wrap">
        <div class="relative flex-1 min-w-[200px]">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size:18px">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar prospecto..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
        </div>
        <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">Todos los estados</option>
            @foreach(\App\Models\Prospect::STATUSES as $key => $label)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="priority" onchange="this.form.submit()" class="border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">Prioridad</option>
            @foreach(\App\Models\Prospect::PRIORITIES as $key => $label)
                <option value="{{ $key }}" {{ request('priority') === $key ? 'selected' : '' }}>{{ $label }} ({{ $key }})</option>
            @endforeach
        </select>
        <select name="category" onchange="this.form.submit()" class="border border-gray-200 rounded-xl text-sm py-2.5 px-3 focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">Categoría</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        @if(request()->hasAny(['search', 'status', 'priority', 'category']))
            <a href="{{ route('prospects.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <span class="material-symbols-outlined" style="font-size:16px">close</span> Limpiar
            </a>
        @endif
    </form>
    <a href="{{ route('prospects.create') }}" class="inline-flex items-center gap-2 bg-brand-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm whitespace-nowrap">
        <span class="material-symbols-outlined" style="font-size:18px">person_add</span>
        Nuevo Prospecto
    </a>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/80 text-left">
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Empresa</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden md:table-cell">Categoría</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden lg:table-cell">Municipio</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden md:table-cell">Teléfono</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Estado</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden lg:table-cell">Prioridad</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider hidden xl:table-cell">Seguimiento</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($prospects as $prospect)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($prospect->logo_path)
                                <img src="{{ asset('storage/' . $prospect->logo_path) }}" class="w-8 h-8 rounded-lg object-cover" alt="">
                            @else
                                <div class="w-8 h-8 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($prospect->business_name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('prospects.show', $prospect) }}" class="font-medium text-gray-800 hover:text-brand-600">{{ $prospect->business_name }}</a>
                                @if($prospect->contact_name)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $prospect->contact_name }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-600 hidden md:table-cell">{{ $prospect->category ?? '—' }}</td>
                    <td class="px-5 py-4 text-gray-600 hidden lg:table-cell">{{ $prospect->municipality->name ?? '—' }}</td>
                    <td class="px-5 py-4 text-gray-600 hidden md:table-cell">{{ $prospect->phone ?? '—' }}</td>
                    <td class="px-5 py-4">
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
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell">
                        @if($prospect->priority)
                            @php
                                $prioColors = ['A' => 'bg-red-100 text-red-700', 'B' => 'bg-amber-100 text-amber-700', 'C' => 'bg-gray-100 text-gray-600'];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $prioColors[$prospect->priority] ?? '' }}">{{ $prospect->priority }}</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 hidden xl:table-cell">
                        @if($prospect->next_followup_date)
                            @php $isOverdue = $prospect->next_followup_date->isPast(); @endphp
                            <span class="text-xs {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                {{ $prospect->next_followup_date->format('d/m/Y') }}
                                @if($isOverdue)
                                    <span class="material-symbols-outlined align-middle" style="font-size:14px">warning</span>
                                @endif
                            </span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('prospects.show', $prospect) }}" class="p-2 text-gray-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition" title="Ver">
                                <span class="material-symbols-outlined" style="font-size:18px">visibility</span>
                            </a>
                            <a href="{{ route('prospects.edit', $prospect) }}" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Editar">
                                <span class="material-symbols-outlined" style="font-size:18px">edit</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center">
                        <span class="material-symbols-outlined text-gray-300" style="font-size:48px">person_search</span>
                        <p class="text-sm text-gray-400 mt-2">No se encontraron prospectos</p>
                        <a href="{{ route('prospects.create') }}" class="inline-flex items-center gap-1 text-sm text-brand-600 font-medium mt-2 hover:text-brand-700">
                            <span class="material-symbols-outlined" style="font-size:16px">add</span> Agregar el primero
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($prospects->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $prospects->links() }}
    </div>
    @endif
</div>
@endsection
