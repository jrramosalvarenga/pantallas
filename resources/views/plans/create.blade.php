@extends('layouts.app')

@section('title', 'Nuevo Plan')
@section('page-title', 'Nuevo Plan')
@section('page-subtitle', 'Crear un paquete publicitario')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('plans.store') }}" class="space-y-6" x-data="planForm()">
        @csrf

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-500" style="font-size:20px">loyalty</span>
                Datos del Plan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre del plan *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ej: Imagen Individual"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipo *</label>
                    <select name="type" x-model="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="individual">Individual (1 pantalla)</option>
                        <option value="combo">Combo (2+ pantallas)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipo de anuncio *</label>
                    <select name="ad_type_id" x-model="adTypeId" @change="updatePrice()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="">Seleccionar</option>
                        @foreach($adTypes as $adType)
                        <option value="{{ $adType->id }}" data-price="{{ $adType->price_per_spot }}">{{ $adType->name }} — L {{ number_format($adType->price_per_spot, 2) }}/spot</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nº de pantallas *</label>
                    <input type="number" name="screens_count" x-model="screensCount" @input="calcFinal()" min="1" value="{{ old('screens_count', 1) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Descuento (%)</label>
                    <input type="number" name="discount_percent" x-model="discount" @input="calcFinal()" step="0.01" min="0" max="100" value="{{ old('discount_percent', 0) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Duración (meses) *</label>
                    <input type="number" name="duration_months" min="1" value="{{ old('duration_months', 1) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div class="flex items-end">
                    <div class="bg-brand-50 rounded-xl px-4 py-3 w-full">
                        <p class="text-xs text-brand-500 font-medium">Precio final/mes</p>
                        <p class="text-2xl font-bold text-brand-700">L <span x-text="finalPrice.toLocaleString('en', {minimumFractionDigits: 2})">0.00</span></p>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Descripción</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none resize-none"
                              placeholder="Descripción opcional del plan">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white rounded-xl text-sm font-medium hover:bg-brand-700 transition shadow-sm">Crear Plan</button>
            <a href="{{ route('plans.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-200 transition">Cancelar</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function planForm() {
    return {
        type: '{{ old("type", "individual") }}',
        adTypeId: '{{ old("ad_type_id", "") }}',
        screensCount: {{ old('screens_count', 1) }},
        discount: {{ old('discount_percent', 0) }},
        basePrice: 0,
        finalPrice: 0,
        updatePrice() {
            const opt = document.querySelector(`select[name="ad_type_id"] option[value="${this.adTypeId}"]`);
            this.basePrice = opt ? parseFloat(opt.dataset.price || 0) : 0;
            this.calcFinal();
        },
        calcFinal() {
            const total = this.basePrice * this.screensCount;
            this.finalPrice = total * (1 - this.discount / 100);
        }
    }
}
</script>
@endpush
@endsection
