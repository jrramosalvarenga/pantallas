<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cliente (opcional)</label>
        <select name="client_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">— Sin cliente —</option>
            @foreach($clients as $client)
            <option value="{{ $client->id }}" {{ old('client_id', $record->client_id ?? '') == $client->id ? 'selected' : '' }}>{{ $client->business_name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Contrato (opcional)</label>
        <select name="contract_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">— Sin contrato —</option>
            @foreach($contracts as $contract)
            <option value="{{ $contract->id }}" {{ old('contract_id', $record->contract_id ?? '') == $contract->id ? 'selected' : '' }}>{{ $contract->contract_number }} — {{ $contract->client->business_name ?? '' }}</option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Descripción</label>
    <input type="text" name="description" value="{{ old('description', $record->description ?? '') }}" required
           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none"
           placeholder="Ej: Pago mensual publicidad octubre">
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Monto (L)</label>
        <input type="number" name="amount" value="{{ old('amount', $record->amount ?? '') }}" step="0.01" min="0" required
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha</label>
        <input type="date" name="date" value="{{ old('date', isset($record) ? $record->date->format('Y-m-d') : date('Y-m-d')) }}" required
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Referencia (opcional)</label>
    <input type="text" name="reference" value="{{ old('reference', $record->reference ?? '') }}"
           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none"
           placeholder="Ej: Recibo #001, Transferencia, etc.">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Notas (opcional)</label>
    <textarea name="notes" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('notes', $record->notes ?? '') }}</textarea>
</div>
