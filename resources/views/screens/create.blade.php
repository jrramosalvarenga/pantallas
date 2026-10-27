@extends('layouts.app')

@section('title', 'Nueva Pantalla')
@section('page-title', 'Nueva Pantalla')
@section('page-subtitle', 'Registrar una nueva pantalla LED')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
        <form method="POST" action="{{ route('screens.store') }}">
            @include('screens._form')

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center gap-2 bg-brand-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition shadow-sm">
                    <span class="material-symbols-outlined" style="font-size:18px">save</span>
                    Guardar Pantalla
                </button>
                <a href="{{ route('screens.index') }}" class="inline-flex items-center gap-2 bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
