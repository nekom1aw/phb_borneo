{{-- Unggah gambar: klik atau tarik-lepas. preview = URL gambar (unggahan baru atau yang tersimpan) --}}
@props(['model', 'id' => null, 'preview' => null, 'aspect' => 'aspect-[4/3]', 'hint' => 'JPG, PNG, atau WebP. Maks 4 MB.', 'fit' => 'object-cover'])

@php $id = $id ?? $model; @endphp

<div x-data="cmsDropzone"
     x-on:livewire-upload-start="uploading = true; progress = 0"
     x-on:livewire-upload-finish="uploading = false"
     x-on:livewire-upload-error="uploading = false"
     x-on:livewire-upload-progress="progress = $event.detail.progress"
     @dragover.prevent="over = true" @dragleave.prevent="over = false" @drop.prevent="drop($event)">
    <label for="{{ $id }}" class="group relative block cursor-pointer overflow-hidden rounded-2xl transition {{ $aspect }} {{ $preview ? 'bg-stone-100' : 'border-2 border-dashed border-stone-300 bg-stone-50 hover:border-phb-green/60 hover:bg-phb-green/5' }}"
           :class="over && 'ring-4 ring-phb-green/30 border-phb-green!'">
        @if ($preview)
            <img src="{{ $preview }}" alt="Pratinjau gambar" class="h-full w-full {{ $fit }}">
            <span class="absolute inset-0 flex items-center justify-center bg-stone-900/0 text-sm font-semibold text-white opacity-0 transition group-hover:bg-stone-900/45 group-hover:opacity-100">
                <span class="flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 backdrop-blur"><x-cms.icon name="upload" class="h-4 w-4" /> Ganti foto</span>
            </span>
        @else
            <span class="flex h-full flex-col items-center justify-center gap-2 p-6 text-center">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-phb-green shadow-sm"><x-cms.icon name="image" class="h-6 w-6" /></span>
                <span class="text-sm font-semibold text-stone-700">Klik untuk memilih foto</span>
                <span class="text-xs text-stone-500">atau tarik foto ke sini</span>
            </span>
        @endif

        {{-- progres unggah --}}
        <span x-cloak x-show="uploading" class="absolute inset-x-4 bottom-4 overflow-hidden rounded-full bg-white/80 shadow">
            <span class="block h-2 rounded-full bg-phb-green transition-all" :style="`width: ${progress}%`"></span>
        </span>
    </label>
    <input x-ref="input" id="{{ $id }}" type="file" wire:model="{{ $model }}" accept="image/png,image/jpeg,image/webp" class="sr-only">
    <p class="mt-2 text-[13px] text-stone-500" x-show="!uploading">{{ $hint }}</p>
    <p x-cloak class="mt-2 text-[13px] font-medium text-phb-green" x-show="uploading">Mengunggah… <span x-text="progress + '%'"></span></p>
</div>
