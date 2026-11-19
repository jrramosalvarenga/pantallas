<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-brand-600" style="font-size:20px">celebration</span>
        Información del Evento
    </h4>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del evento *</label>
            <input type="text" name="name" value="{{ old('name', $event->name ?? '') }}" required
                   class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none"
                   placeholder="Ej: Boda García - López">
            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de evento *</label>
            <select name="type" required class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                @foreach(\App\Models\Event::TYPES as $k => $v)
                    <option value="{{ $k }}" {{ old('type', $event->type ?? 'otro') === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
            <select name="status" required class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                @foreach(\App\Models\Event::STATUSES as $k => $v)
                    <option value="{{ $k }}" {{ old('status', $event->status ?? 'cotizado') === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha y hora inicio *</label>
            <input type="datetime-local" name="start_date" value="{{ old('start_date', isset($event) ? $event->start_date->format('Y-m-d\TH:i') : '') }}" required
                   class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
            @error('start_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha y hora fin *</label>
            <input type="datetime-local" name="end_date" value="{{ old('end_date', isset($event) ? $event->end_date->format('Y-m-d\TH:i') : '') }}" required
                   class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
            @error('end_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lugar *</label>
            <input type="text" name="venue" value="{{ old('venue', $event->venue ?? '') }}" required
                   class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none"
                   placeholder="Salón de eventos, plaza, etc.">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección del lugar</label>
            <input type="text" name="venue_address" value="{{ old('venue_address', $event->venue_address ?? '') }}"
                   class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
            <select name="municipality_id" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                <option value="">— Seleccionar —</option>
                @foreach($municipalities as $m)
                    <option value="{{ $m->id }}" {{ old('municipality_id', $event->municipality_id ?? '') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pantalla asignada</label>
            <select name="screen_id" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                <option value="">— Pantalla móvil —</option>
                @foreach($screens as $screen)
                    <option value="{{ $screen->id }}" {{ old('screen_id', $event->screen_id ?? '') == $screen->id ? 'selected' : '' }}>{{ $screen->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
            <select name="client_id" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
                <option value="">— Sin cliente asociado —</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id', $event->client_id ?? '') == $client->id ? 'selected' : '' }}>{{ $client->business_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-emerald-600" style="font-size:20px">payments</span>
        Precio y Contacto
    </h4>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Precio cotizado (L.)</label>
            <input type="number" name="quoted_price" value="{{ old('quoted_price', $event->quoted_price ?? '') }}" step="0.01" min="0"
                   class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Precio final (L.)</label>
            <input type="number" name="final_price" value="{{ old('final_price', $event->final_price ?? '') }}" step="0.01" min="0"
                   class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Anticipo (L.)</label>
            <input type="number" name="deposit_amount" value="{{ old('deposit_amount', $event->deposit_amount ?? '') }}" step="0.01" min="0"
                   class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
        </div>
        <div class="flex items-end pb-1">
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="deposit_paid" value="1" {{ old('deposit_paid', $event->deposit_paid ?? false) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                <span class="text-sm text-gray-700">Anticipo pagado</span>
            </label>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de contacto</label>
            <input type="text" name="contact_name" value="{{ old('contact_name', $event->contact_name ?? '') }}"
                   class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono de contacto</label>
            <input type="text" name="contact_phone" value="{{ old('contact_phone', $event->contact_phone ?? '') }}"
                   class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none">
        </div>
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Notas técnicas</label>
            <textarea name="technical_notes" rows="2" placeholder="Requisitos técnicos: energía, espacio, horario montaje..."
                      class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('technical_notes', $event->technical_notes ?? '') }}</textarea>
        </div>
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Notas generales</label>
            <textarea name="notes" rows="2" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('notes', $event->notes ?? '') }}</textarea>
        </div>
    </div>
</div>
