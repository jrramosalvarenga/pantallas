@extends('layouts.app')

@section('title', 'Nuevo Contrato')
@section('page-title', 'Nuevo Contrato')
@section('page-subtitle', 'Registrar un contrato publicitario')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('contracts.store') }}" class="space-y-6" x-data="contractForm()">
        @csrf

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-500" style="font-size:20px">person</span>
                Datos del Contrato
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cliente *</label>
                    <select name="client_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="">Seleccionar cliente</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->business_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Plan *</label>
                    <select name="plan_id" required x-model="planId" @change="updatePrice()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="">Seleccionar plan</option>
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" data-price="{{ $plan->final_price }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} — L {{ number_format($plan->final_price, 2) }}/mes ({{ $plan->adType->name ?? '' }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha de inicio *</label>
                    <input type="date" name="start_date" x-model="startDate" @change="calcTotal()" value="{{ old('start_date') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha de fin *</label>
                    <input type="date" name="end_date" x-model="endDate" @change="calcTotal()" value="{{ old('end_date') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Frecuencia de pago</label>
                    <select name="payment_frequency" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="mensual">Mensual</option>
                        <option value="trimestral">Trimestral</option>
                        <option value="semestral">Semestral</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <div class="bg-brand-50 rounded-xl px-4 py-3 w-full">
                        <p class="text-xs text-brand-500 font-medium">Total estimado</p>
                        <p class="text-xl font-bold text-brand-700">L <span x-text="total.toLocaleString('en', {minimumFractionDigits: 2})">0.00</span></p>
                        <p class="text-[10px] text-brand-400" x-text="months + ' mes(es)'"></p>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Notas</label>
                <textarea name="notes" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-500" style="font-size:20px">tv</span>
                Pantallas y Espacios
            </h3>
            <div class="space-y-3">
                @foreach($screens as $screen)
                <label class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:border-brand-300 hover:bg-brand-50/30 transition cursor-pointer">
                    <input type="checkbox" x-model="selectedScreens" value="{{ $screen->id }}" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700">{{ $screen->name }}</p>
                        <p class="text-xs text-gray-400">{{ $screen->municipality->name ?? '' }} · {{ $screen->available_spaces }} espacios disponibles de {{ $screen->total_spaces }}</p>
                        <input type="hidden" name="screens[{{ $loop->index }}][id]" value="{{ $screen->id }}" :disabled="!selectedScreens.includes('{{ $screen->id }}')">
                    </div>
                    <div class="w-24" x-show="selectedScreens.includes('{{ $screen->id }}')">
                        <label class="text-[10px] text-gray-500">Espacio Nº</label>
                        <input type="number" name="screens[{{ $loop->index }}][space_number]" min="1" max="{{ $screen->total_spaces }}" value="1"
                               class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white rounded-xl text-sm font-medium hover:bg-brand-700 transition shadow-sm">
                Crear Contrato
            </button>
            <a href="{{ route('contracts.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                Cancelar
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function contractForm() {
    return {
        planId: '{{ old("plan_id", "") }}',
        startDate: '{{ old("start_date", "") }}',
        endDate: '{{ old("end_date", "") }}',
        selectedScreens: [],
        price: 0,
        months: 0,
        total: 0,
        updatePrice() {
            const opt = document.querySelector(`select[name="plan_id"] option[value="${this.planId}"]`);
            this.price = opt ? parseFloat(opt.dataset.price || 0) : 0;
            this.calcTotal();
        },
        calcTotal() {
            if (this.startDate && this.endDate) {
                const s = new Date(this.startDate), e = new Date(this.endDate);
                this.months = Math.max(1, (e.getFullYear() - s.getFullYear()) * 12 + e.getMonth() - s.getMonth());
            } else { this.months = 0; }
            this.total = this.price * this.months;
        }
    }
}
</script>
@endpush
@endsection
