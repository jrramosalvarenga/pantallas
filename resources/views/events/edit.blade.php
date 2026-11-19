@extends('layouts.app')

@section('title', 'Editar Evento')
@section('page-title', 'Editar Evento')
@section('page-subtitle', $event->name)

@section('content')
<div class="max-w-4xl">
    <div class="mb-4">
        <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-brand-600">
            <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Volver al detalle
        </a>
    </div>

    <form method="POST" action="{{ route('events.update', $event) }}" class="space-y-6">
        @csrf @method('PUT')
        @include('events._form')

        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 bg-brand-600 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm">
                <span class="material-symbols-outlined" style="font-size:18px">save</span> Guardar Cambios
            </button>
            <a href="{{ route('events.show', $event) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
        </div>
    </form>
</div>
@endsection
