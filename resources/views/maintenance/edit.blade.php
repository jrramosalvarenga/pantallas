@extends('layouts.app')

@section('title', 'Editar Mantenimiento')
@section('page-title', 'Editar Mantenimiento')
@section('page-subtitle', $maintenance->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('maintenance.update', $maintenance) }}" class="space-y-5">
            @csrf @method('PUT')
            @include('maintenance._form', ['record' => $maintenance])

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('maintenance.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 border border-gray-200 rounded-xl transition">Cancelar</a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition shadow-sm">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
