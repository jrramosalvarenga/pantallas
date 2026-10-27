<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pantalla</label>
        <select name="screen_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">— Seleccionar —</option>
            @foreach($screens as $screen)
            <option value="{{ $screen->id }}" {{ old('screen_id', $record->screen_id ?? '') == $screen->id ? 'selected' : '' }}>{{ $screen->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha</label>
        <input type="date" name="date" value="{{ old('date', isset($record) ? $record->date->format('Y-m-d') : date('Y-m-d')) }}" required
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipo</label>
        <select name="type" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="preventivo" {{ old('type', $record->type ?? '') === 'preventivo' ? 'selected' : '' }}>Preventivo</option>
            <option value="correctivo" {{ old('type', $record->type ?? '') === 'correctivo' ? 'selected' : '' }}>Correctivo</option>
            <option value="emergencia" {{ old('type', $record->type ?? '') === 'emergencia' ? 'selected' : '' }}>Emergencia</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Estado</label>
        <select name="status" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="programado" {{ old('status', $record->status ?? '') === 'programado' ? 'selected' : '' }}>Programado</option>
            <option value="en_curso" {{ old('status', $record->status ?? '') === 'en_curso' ? 'selected' : '' }}>En curso</option>
            <option value="completado" {{ old('status', $record->status ?? 'completado') === 'completado' ? 'selected' : '' }}>Completado</option>
            <option value="cancelado" {{ old('status', $record->status ?? '') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
        </select>
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Título</label>
    <input type="text" name="title" value="{{ old('title', $record->title ?? '') }}" required
           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none"
           placeholder="Ej: Limpieza de módulos LED">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Descripción</label>
    <textarea name="description" rows="3" required
              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('description', $record->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Técnico</label>
        <input type="text" name="technician" value="{{ old('technician', $record->technician ?? '') }}"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Costo (L)</label>
        <input type="number" name="cost" value="{{ old('cost', $record->cost ?? '0') }}" step="0.01" min="0"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Próximo mantenimiento</label>
        <input type="date" name="next_maintenance_date" value="{{ old('next_maintenance_date', isset($record) && $record->next_maintenance_date ? $record->next_maintenance_date->format('Y-m-d') : '') }}"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Notas (opcional)</label>
    <textarea name="notes" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('notes', $record->notes ?? '') }}</textarea>
</div>
