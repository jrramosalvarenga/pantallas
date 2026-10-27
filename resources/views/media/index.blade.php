@extends('layouts.app')

@section('title', 'Medios Publicitarios')
@section('page-title', 'Medios Publicitarios')
@section('page-subtitle', 'Repositorio de artes y videos')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <select name="type" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-brand-500 outline-none" onchange="this.form.submit()">
            <option value="">Todos los tipos</option>
            <option value="imagen" {{ request('type') === 'imagen' ? 'selected' : '' }}>Imagen</option>
            <option value="arte_animado" {{ request('type') === 'arte_animado' ? 'selected' : '' }}>Arte animado</option>
            <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Video</option>
        </select>
        <select name="client_id" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-brand-500 outline-none" onchange="this.form.submit()">
            <option value="">Todos los clientes</option>
            @foreach($clients as $client)
            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->business_name }}</option>
            @endforeach
        </select>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size:18px">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
                   class="pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 outline-none">
        </div>
    </form>
    <a href="{{ route('media.create') }}" class="inline-flex items-center gap-2 bg-brand-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm">
        <span class="material-symbols-outlined" style="font-size:18px">cloud_upload</span>
        Subir archivo
    </a>
</div>

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
    @forelse($media as $item)
    <a href="{{ route('media.show', $item) }}" class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="aspect-square bg-gray-100 relative overflow-hidden">
            @if($item->thumbnail_path)
                <img src="{{ asset('storage/' . $item->thumbnail_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                    <span class="material-symbols-outlined text-gray-400" style="font-size:40px">
                        {{ $item->type === 'video' ? 'videocam' : ($item->type === 'arte_animado' ? 'animation' : 'image') }}
                    </span>
                </div>
            @endif
            @if($item->type === 'video')
            <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/30 transition">
                <div class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-brand-600" style="font-size:24px">play_arrow</span>
                </div>
            </div>
            @endif
            <span class="absolute top-2 right-2 text-[10px] font-semibold px-2 py-0.5 rounded-full
                {{ $item->type === 'imagen' ? 'bg-blue-500 text-white' : ($item->type === 'video' ? 'bg-red-500 text-white' : 'bg-purple-500 text-white') }}">
                {{ str_replace('_', ' ', $item->type) }}
            </span>
        </div>
        <div class="p-3">
            <p class="text-xs font-medium text-gray-700 truncate">{{ $item->name }}</p>
            <p class="text-[10px] text-gray-400 mt-0.5">{{ $item->file_size_human }} · {{ $item->client->business_name ?? 'Sin cliente' }}</p>
        </div>
    </a>
    @empty
    <div class="col-span-full text-center py-16">
        <span class="material-symbols-outlined text-gray-300" style="font-size:64px">perm_media</span>
        <p class="text-gray-400 mt-3">No hay archivos multimedia aún</p>
        <a href="{{ route('media.create') }}" class="inline-flex items-center gap-2 mt-4 text-sm text-brand-600 font-medium hover:text-brand-700">
            <span class="material-symbols-outlined" style="font-size:16px">add</span> Subir primer archivo
        </a>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $media->withQueryString()->links() }}
</div>
@endsection
