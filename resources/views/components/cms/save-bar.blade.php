{{-- Bar simpan yang menempel di bawah layar (dipakai di dalam x-data="cmsForm(...)") --}}
@props(['label' => 'Simpan perubahan', 'target' => 'save'])

<div class="sticky bottom-4 z-20 flex flex-col gap-3 rounded-2xl border border-stone-200 bg-white/95 p-3 shadow-[0_8px_30px_-10px_rgba(28,25,23,0.25)] backdrop-blur sm:flex-row sm:items-center sm:justify-between sm:pl-5">
    <p class="flex items-center gap-2 text-sm">
        <span x-show="!dirty" class="flex items-center gap-2 text-stone-500"><x-cms.icon name="check-circle" class="h-4 w-4 text-emerald-600" /> Tidak ada perubahan yang belum disimpan.</span>
        <span x-cloak x-show="dirty" class="flex items-center gap-2 font-semibold text-amber-700"><span class="h-2 w-2 rounded-full bg-amber-500"></span> Ada perubahan yang belum disimpan.</span>
    </p>
    <button type="button" wire:click="{{ $target }}" @click="dirty = false" class="cms-btn-primary h-12 px-6" wire:loading.attr="disabled" {{ $attributes }}>
        <x-cms.icon name="save" class="h-4 w-4" />
        <span wire:loading.remove wire:target="{{ $target }}">{{ $label }}</span>
        <span wire:loading wire:target="{{ $target }}">Menyimpan…</span>
    </button>
</div>
