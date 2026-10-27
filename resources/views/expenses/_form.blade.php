<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Categoría</label>
        <select name="expense_category_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">— Seleccionar —</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('expense_category_id', $record->expense_category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha</label>
        <input type="date" name="date" value="{{ old('date', isset($record) ? $record->date->format('Y-m-d') : date('Y-m-d')) }}" required
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Descripción</label>
    <input type="text" name="description" value="{{ old('description', $record->description ?? '') }}" required
           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none"
           placeholder="Ej: Pago de energía eléctrica octubre">
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Monto (L)</label>
        <input type="number" name="amount" value="{{ old('amount', $record->amount ?? '') }}" step="0.01" min="0" required
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Recurrencia</label>
        <select name="recurrence" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="unico" {{ old('recurrence', $record->recurrence ?? 'unico') === 'unico' ? 'selected' : '' }}>Único</option>
            <option value="mensual" {{ old('recurrence', $record->recurrence ?? '') === 'mensual' ? 'selected' : '' }}>Mensual</option>
            <option value="trimestral" {{ old('recurrence', $record->recurrence ?? '') === 'trimestral' ? 'selected' : '' }}>Trimestral</option>
            <option value="anual" {{ old('recurrence', $record->recurrence ?? '') === 'anual' ? 'selected' : '' }}>Anual</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pantalla (opcional)</label>
        <select name="screen_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">General</option>
            @foreach($screens as $screen)
            <option value="{{ $screen->id }}" {{ old('screen_id', $record->screen_id ?? '') == $screen->id ? 'selected' : '' }}>{{ $screen->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Referencia (opcional)</label>
    <input type="text" name="reference" value="{{ old('reference', $record->reference ?? '') }}"
           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none"
           placeholder="Ej: Factura #001, Recibo, etc.">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Notas (opcional)</label>
    <textarea name="notes" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('notes', $record->notes ?? '') }}</textarea>
</div>
