@extends('layouts.app')

@section('title', 'Editar Medio')
@section('page-title', 'Editar Medio')
@section('page-subtitle', $media->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('media.update', $media) }}" class="space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $media->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipo</label>
                    <select name="type" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="imagen" {{ old('type', $media->type) === 'imagen' ? 'selected' : '' }}>Imagen</option>
                        <option value="arte_animado" {{ old('type', $media->type) === 'arte_animado' ? 'selected' : '' }}>Arte animado</option>
                        <option value="video" {{ old('type', $media->type) === 'video' ? 'selected' : '' }}>Video</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Estado</label>
                    <select name="status" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="activo" {{ old('status', $media->status) === 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('status', $media->status) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        <option value="en_revision" {{ old('status', $media->status) === 'en_revision' ? 'selected' : '' }}>En revisión</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cliente</label>
                    <select name="client_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="">— Sin cliente —</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $media->client_id) == $client->id ? 'selected' : '' }}>{{ $client->business_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Contrato</label>
                <select name="contract_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    <option value="">— Sin contrato —</option>
                    @foreach($contracts as $contract)
                    <option value="{{ $contract->id }}" {{ old('contract_id', $media->contract_id) == $contract->id ? 'selected' : '' }}>{{ $contract->contract_number }} — {{ $contract->client->business_name ?? '' }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Notas</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('notes', $media->notes) }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('media.show', $media) }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 border border-gray-200 rounded-xl transition">Cancelar</a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition shadow-sm">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
