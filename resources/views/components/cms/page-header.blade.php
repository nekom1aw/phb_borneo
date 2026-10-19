{{-- Judul halaman CMS + penjelasan singkat + tombol aksi (slot) --}}
@props(['title', 'desc' => null, 'icon' => null, 'back' => null, 'backLabel' => null])

<div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
    <div class="min-w-0">
        @if ($back)
            <a href="{{ $back }}" class="mb-3 inline-flex items-center gap-1.5 text-sm font-semibold text-stone-500 hover:text-phb-green">
                <x-cms.icon name="arrow-left" class="h-4 w-4" /> {{ $backLabel }}
            </a>
        @endif
        <div class="flex items-center gap-3">
            @if ($icon)
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-phb-gold/15 text-phb-gold">
                    <x-cms.icon :name="$icon" class="h-6 w-6" />
                </span>
            @endif
            <h1 class="text-2xl font-bold tracking-tight text-stone-900 md:text-[28px]">{{ $title }}</h1>
        </div>
        @if ($desc)
            <p class="mt-2 max-w-2xl text-[15px] leading-relaxed text-stone-500">{{ $desc }}</p>
        @endif
    </div>
    @if (trim($slot) !== '')
        <div class="flex shrink-0 flex-wrap gap-2">{{ $slot }}</div>
    @endif
</div>
