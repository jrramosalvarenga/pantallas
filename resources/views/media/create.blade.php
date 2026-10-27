@extends('layouts.app')

@section('title', 'Subir Medio')
@section('page-title', 'Subir Medio')
@section('page-subtitle', 'Agregar arte o video publicitario')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data"
              x-data="fileUpload()" class="space-y-6">
            @csrf

            {{-- Drag & Drop Zone --}}
            <div class="relative border-2 border-dashed rounded-2xl p-8 text-center transition-colors"
                 :class="dragOver ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-gray-300'"
                 @dragover.prevent="dragOver = true"
                 @dragleave.prevent="dragOver = false"
                 @drop.prevent="handleDrop($event)">
                <input type="file" name="file" id="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".jpg,.jpeg,.png,.gif,.webp,.mp4,.avi,.mov" @change="handleFileSelect($event)" required>
                <div x-show="!fileName">
                    <span class="material-symbols-outlined text-gray-400 mb-2" style="font-size:48px">cloud_upload</span>
                    <p class="text-sm text-gray-600 font-medium">Arrastra y suelta tu archivo aquí</p>
                    <p class="text-xs text-gray-400 mt-1">o haz clic para seleccionar</p>
                    <p class="text-xs text-gray-400 mt-2">Imágenes: JPG, PNG, GIF, WebP · Videos: MP4, AVI, MOV · Máx: 100MB</p>
                </div>
                <div x-show="fileName" class="flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined text-brand-500" style="font-size:32px" x-text="isVideo ? 'videocam' : 'image'"></span>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-700" x-text="fileName"></p>
                        <p class="text-xs text-gray-400" x-text="fileSize"></p>
                    </div>
                    <button type="button" @click="clearFile()" class="text-gray-400 hover:text-red-500">
                        <span class="material-symbols-outlined" style="font-size:18px">close</span>
                    </button>
                </div>
                <template x-if="previewUrl && !isVideo">
                    <img :src="previewUrl" class="mt-4 max-h-48 mx-auto rounded-xl shadow-sm">
                </template>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre</label>
                    <input type="text" name="name" x-model="autoName" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipo</label>
                    <select name="type" x-model="autoType" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="imagen">Imagen</option>
                        <option value="arte_animado">Arte animado</option>
                        <option value="video">Video</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cliente (opcional)</label>
                    <select name="client_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="">— Sin cliente —</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->business_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Contrato (opcional)</label>
                    <select name="contract_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <option value="">— Sin contrato —</option>
                        @foreach($contracts as $contract)
                        <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>{{ $contract->contract_number }} — {{ $contract->client->business_name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Notas (opcional)</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 outline-none resize-none">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('media.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 border border-gray-200 rounded-xl transition">Cancelar</a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition shadow-sm">
                    <span class="material-symbols-outlined align-middle mr-1" style="font-size:16px">upload</span>
                    Subir archivo
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function fileUpload() {
    return {
        dragOver: false,
        fileName: '',
        fileSize: '',
        isVideo: false,
        previewUrl: null,
        autoName: '{{ old('name') }}',
        autoType: '{{ old('type', 'imagen') }}',
        handleDrop(e) {
            this.dragOver = false;
            const file = e.dataTransfer.files[0];
            if (file) {
                document.getElementById('file').files = e.dataTransfer.files;
                this.processFile(file);
            }
        },
        handleFileSelect(e) {
            const file = e.target.files[0];
            if (file) this.processFile(file);
        },
        processFile(file) {
            this.fileName = file.name;
            this.fileSize = this.formatSize(file.size);
            this.isVideo = file.type.startsWith('video/');
            this.autoType = this.isVideo ? 'video' : 'imagen';
            if (!this.autoName) {
                this.autoName = file.name.replace(/\.[^/.]+$/, '');
            }
            if (file.type.startsWith('image/')) {
                this.previewUrl = URL.createObjectURL(file);
            } else {
                this.previewUrl = null;
            }
        },
        clearFile() {
            this.fileName = '';
            this.fileSize = '';
            this.previewUrl = null;
            document.getElementById('file').value = '';
        },
        formatSize(bytes) {
            if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
            if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
            return bytes + ' bytes';
        }
    }
}
</script>
@endpush
@endsection
