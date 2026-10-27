@extends('layouts.app')

@section('title', 'Nuevo Ingreso')
@section('page-title', 'Nuevo Ingreso')
@section('page-subtitle', 'Registrar ingreso por publicidad')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('incomes.store') }}" class="space-y-5">
            @csrf
            @include('incomes._form', ['record' => null])

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('incomes.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 border border-gray-200 rounded-xl transition">Cancelar</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition shadow-sm">Registrar ingreso</button>
            </div>
        </form>
    </div>
</div>
@endsection
