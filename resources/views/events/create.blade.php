@extends('layouts.app')

@section('title', 'Nuevo Evento')
@section('page-title', 'Nuevo Evento')
@section('page-subtitle', 'Registrar evento para pantalla móvil')

@section('content')
<div class="max-w-4xl">
    <div class="mb-4">
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-600">
            <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Volver a eventos
        </a>
    </div>

    <form method="POST" action="{{ route('events.store') }}" class="space-y-6">
        @csrf
        @include('events._form')

        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 bg-brand-600 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm">
                <span class="material-symbols-outlined" style="font-size:18px">celebration</span> Crear Evento
            </button>
            <a href="{{ route('events.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
        </div>
    </form>
</div>
@endsection
