@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1.5">Nombre de empresa <span class="text-red-500">*</span></label>
        <input type="text" id="business_name" name="business_name" value="{{ old('business_name', $client->business_name ?? '') }}" required
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="Ej: Ferretería El Éxito">
    </div>
    <div>
        <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-1.5">Nombre del contacto <span class="text-red-500">*</span></label>
        <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name', $client->contact_name ?? '') }}" required
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="Ej: Juan Pérez">
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Correo electrónico</label>
        <input type="email" id="email" name="email" value="{{ old('email', $client->email ?? '') }}"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="correo@ejemplo.com">
    </div>
    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Teléfono</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $client->phone ?? '') }}"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="Ej: 9999-9999">
    </div>
    <div>
        <label for="rtn" class="block text-sm font-medium text-gray-700 mb-1.5">RTN</label>
        <input type="text" id="rtn" name="rtn" value="{{ old('rtn', $client->rtn ?? '') }}"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="Ej: 0801-1990-00001">
    </div>
    <div>
        <label for="municipality_id" class="block text-sm font-medium text-gray-700 mb-1.5">Municipio</label>
        <select id="municipality_id" name="municipality_id"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none bg-white">
            <option value="">Seleccionar...</option>
            @foreach($municipalities as $m)
                <option value="{{ $m->id }}" {{ old('municipality_id', $client->municipality_id ?? '') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Dirección</label>
        <input type="text" id="address" name="address" value="{{ old('address', $client->address ?? '') }}"
               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
               placeholder="Dirección completa">
    </div>
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Estado <span class="text-red-500">*</span></label>
        <select id="status" name="status" required
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none bg-white">
            @foreach(['activo' => 'Activo', 'inactivo' => 'Inactivo', 'suspendido' => 'Suspendido'] as $val => $label)
                <option value="{{ $val }}" {{ old('status', $client->status ?? 'activo') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Notas</label>
        <textarea id="notes" name="notes" rows="3"
                  class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none resize-none"
                  placeholder="Notas adicionales sobre el cliente...">{{ old('notes', $client->notes ?? '') }}</textarea>
    </div>
</div>
