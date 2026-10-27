@extends('layouts.app')

@section('title', $media->name)
@section('page-title', 'Detalle del Medio')
@section('page-subtitle', $media->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Preview --}}
        <div class="bg-gray-900 flex items-center justify-center min-h-[300px] max-h-[500px]">
            @if(str_starts_with($media->mime_type ?? '', 'image/'))
                <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->name }}" class="max-w-full max-h-[500px] object-contain">
            @elseif(str_starts_with($media->mime_type ?? '', 'video/'))
                <video controls class="max-w-full max-h-[500px]">
                    <source src="{{ asset('storage/' . $media->file_path) }}" type="{{ $media->mime_type }}">
                    Tu navegador no soporta la reproducción de video.
                </video>
            @else
                <div class="text-center py-16">
                    <span class="material-symbols-outlined text-gray-500" style="font-size:64px">perm_media</span>
                    <p class="text-gray-400 mt-2">Vista previa no disponible</p>
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="p-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $media->name }}</h2>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $media->type === 'imagen' ? 'bg-blue-50 text-blue-600' : ($media->type === 'video' ? 'bg-red-50 text-red-600' : 'bg-purple-50 text-purple-600') }}">
                            {{ str_replace('_', ' ', ucfirst($media->type)) }}
                        </span>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $media->status === 'activo' ? 'bg-emerald-50 text-emerald-600' : ($media->status === 'en_revision' ? 'bg-amber-50 text-amber-600' : 'bg-gray-100 text-gray-500') }}">
                            {{ ucfirst(str_replace('_', ' ', $media->status)) }}
                        </span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('media.edit', $media) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                        <span class="material-symbols-outlined" style="font-size:16px">edit</span> Editar
                    </a>
                    <form method="POST" action="{{ route('media.destroy', $media) }}" onsubmit="return confirm('¿Eliminar este archivo?')">
                        @csrf @method('DELETE')
                        <button class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-xl hover:bg-red-50 transition">
                            <span class="material-symbols-outlined" style="font-size:16px">delete</span> Eliminar
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-[10px] text-gray-400 uppercase font-semibold">Tamaño</p>
                    <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $media->file_size_human }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-[10px] text-gray-400 uppercase font-semibold">Formato</p>
                    <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ strtoupper(pathinfo($media->file_path, PATHINFO_EXTENSION)) }}</p>
                </div>
                @if($media->width && $media->height)
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-[10px] text-gray-400 uppercase font-semibold">Dimensiones</p>
                    <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $media->width }}×{{ $media->height }}px</p>
                </div>
                @endif
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-[10px] text-gray-400 uppercase font-semibold">Subido</p>
                    <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $media->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            @if($media->client)
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <span class="material-symbols-outlined text-gray-400" style="font-size:16px">business</span>
                Cliente: <span class="font-medium text-gray-800">{{ $media->client->business_name }}</span>
            </div>
            @endif
            @if($media->contract)
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <span class="material-symbols-outlined text-gray-400" style="font-size:16px">description</span>
                Contrato: <span class="font-medium text-gray-800">{{ $media->contract->contract_number }}</span>
            </div>
            @endif
            @if($media->notes)
            <div class="mt-4 bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Notas</p>
                <p class="text-sm text-gray-600">{{ $media->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('media.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Volver al repositorio
        </a>
    </div>
</div>
@endsection
