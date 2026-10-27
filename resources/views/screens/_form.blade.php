@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nombre <span class="text-red-500">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $screen->name ?? '') }}" required
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="Ej: Pantalla 1 — Parque Central">
    </div>
    <div>
        <label for="code" class="block text-sm font-medium text-gray-700 mb-1.5">Código único <span class="text-red-500">*</span></label>
        <input type="text" id="code" name="code" value="{{ old('code', $screen->code ?? '') }}" required
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="Ej: SFP-001">
    </div>
    <div>
        <label for="municipality_id" class="block text-sm font-medium text-gray-700 mb-1.5">Municipio <span class="text-red-500">*</span></label>
        <select id="municipality_id" name="municipality_id" required
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none bg-white">
            <option value="">Seleccionar...</option>
            @foreach($municipalities as $m)
                <option value="{{ $m->id }}" {{ old('municipality_id', $screen->municipality_id ?? '') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Dirección / Ubicación <span class="text-red-500">*</span></label>
        <input type="text" id="address" name="address" value="{{ old('address', $screen->address ?? '') }}" required
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="Ej: Frente al parque central">
    </div>
    <div>
        <label for="gps_lat" class="block text-sm font-medium text-gray-700 mb-1.5">Latitud GPS</label>
        <input type="text" id="gps_lat" name="gps_lat" value="{{ old('gps_lat', $screen->gps_lat ?? '') }}"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="Ej: 14.8406">
    </div>
    <div>
        <label for="gps_lng" class="block text-sm font-medium text-gray-700 mb-1.5">Longitud GPS</label>
        <input type="text" id="gps_lng" name="gps_lng" value="{{ old('gps_lng', $screen->gps_lng ?? '') }}"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="Ej: -86.8503">
    </div>

    {{-- Sección operativa --}}
    <div class="md:col-span-2">
        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 mt-2">Configuración operativa</h4>
    </div>

    <div>
        <label for="total_spaces" class="block text-sm font-medium text-gray-700 mb-1.5">Espacios disponibles <span class="text-red-500">*</span></label>
        <input type="number" id="total_spaces" name="total_spaces" value="{{ old('total_spaces', $screen->total_spaces ?? 24) }}" required min="1"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
    </div>
    <div>
        <label for="spot_duration_seconds" class="block text-sm font-medium text-gray-700 mb-1.5">Duración del spot (seg) <span class="text-red-500">*</span></label>
        <input type="number" id="spot_duration_seconds" name="spot_duration_seconds" value="{{ old('spot_duration_seconds', $screen->spot_duration_seconds ?? 10) }}" required min="1"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
    </div>
    <div>
        <label for="cycle_duration_seconds" class="block text-sm font-medium text-gray-700 mb-1.5">Duración del ciclo (seg) <span class="text-red-500">*</span></label>
        <input type="number" id="cycle_duration_seconds" name="cycle_duration_seconds" value="{{ old('cycle_duration_seconds', $screen->cycle_duration_seconds ?? 240) }}" required min="1"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
    </div>
    <div>
        <label for="operating_hours" class="block text-sm font-medium text-gray-700 mb-1.5">Horario de operación <span class="text-red-500">*</span></label>
        <input type="text" id="operating_hours" name="operating_hours" value="{{ old('operating_hours', $screen->operating_hours ?? '06:00-20:00') }}" required
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="Ej: 06:00-20:00">
    </div>

    {{-- Sección financiera --}}
    <div class="md:col-span-2">
        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 mt-2">Información financiera</h4>
    </div>

    <div>
        <label for="investment_cost" class="block text-sm font-medium text-gray-700 mb-1.5">Costo de inversión (L) <span class="text-red-500">*</span></label>
        <input type="number" id="investment_cost" name="investment_cost" value="{{ old('investment_cost', $screen->investment_cost ?? 0) }}" required min="0" step="0.01"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
    </div>
    <div>
        <label for="book_value" class="block text-sm font-medium text-gray-700 mb-1.5">Valor contable actual (L)</label>
        <input type="number" id="book_value" name="book_value" value="{{ old('book_value', $screen->book_value ?? '') }}" min="0" step="0.01"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="Se calcula del costo si se deja vacío">
    </div>
    <div>
        <label for="useful_life_months" class="block text-sm font-medium text-gray-700 mb-1.5">Vida útil (meses) <span class="text-red-500">*</span></label>
        <input type="number" id="useful_life_months" name="useful_life_months" value="{{ old('useful_life_months', $screen->useful_life_months ?? 60) }}" required min="1"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
    </div>
    <div>
        <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-1.5">Fecha de compra</label>
        <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', isset($screen->purchase_date) ? $screen->purchase_date->format('Y-m-d') : '') }}"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Estado <span class="text-red-500">*</span></label>
        <select id="status" name="status" required
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none bg-white">
            @foreach(['activa' => 'Activa', 'mantenimiento' => 'En mantenimiento', 'inactiva' => 'Inactiva'] as $val => $label)
                <option value="{{ $val }}" {{ old('status', $screen->status ?? 'activa') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Notas</label>
        <textarea id="notes" name="notes" rows="3"
                  class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none resize-none"
                  placeholder="Notas adicionales...">{{ old('notes', $screen->notes ?? '') }}</textarea>
    </div>
</div>

<div class="mt-4 p-4 bg-blue-50 rounded-xl text-sm text-blue-700 flex items-start gap-2">
    <span class="material-symbols-outlined" style="font-size:18px">info</span>
    <span>La depreciación mensual se calcula automáticamente: <strong>Inversión / Vida útil</strong>.</span>
</div>
