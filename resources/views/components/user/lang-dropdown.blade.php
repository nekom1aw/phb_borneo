{{-- Dropdown pilih bahasa. Dipakai di navbar (butuh $langs, $locale, $langUrl); $size: desktop | mobile --}}
@php
    $current = collect($langs)->firstWhere('code', $locale);
    $desktop = ($size ?? 'desktop') === 'desktop';
@endphp

<div x-data="{ langOpen: false }" @click.outside="langOpen = false" @keydown.escape="langOpen = false; $refs.langButton.focus()" class="relative">
    <button type="button" x-ref="langButton" @click="langOpen = !langOpen" :aria-expanded="langOpen" aria-haspopup="true"
            aria-label="{{ __('Choose language') }}: {{ $current['name'] }}"
            class="flex items-center gap-1.5 rounded-full font-bold leading-none tracking-[0.04em] text-white outline-hidden hover:bg-white/15 focus-visible:ring-2 focus-visible:ring-white/70 {{ $desktop ? 'h-u-[41] px-u-[12] text-[max(13px,calc(18*var(--u)))]' : 'h-11 px-3 text-sm' }}"
            :class="langOpen && 'bg-white/15'">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-[1.1em] w-[1.1em]" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a14 14 0 0 1 0 18a14 14 0 0 1 0-18z"/></svg>
        <span>{{ $current['label'] }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" class="h-[0.8em] w-[0.8em] transition-transform duration-200" :class="langOpen && 'rotate-180'" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
    </button>

    <div x-cloak x-show="langOpen"
         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="absolute right-0 top-full z-50 mt-2 w-56 overflow-hidden rounded-xl bg-white py-1 text-phb-text shadow-lg ring-1 ring-black/5">
        @foreach ($langs as $lang)
            <a href="{{ $langUrl($lang['code']) }}" hreflang="{{ $lang['code'] }}" lang="{{ $lang['code'] }}"
               @if ($locale === $lang['code']) aria-current="true" @endif
               class="flex items-center gap-3 px-4 py-3 text-sm normal-case tracking-normal hover:bg-phb-gold/10 {{ $locale === $lang['code'] ? 'font-bold' : '' }}">
                <span class="w-6 text-xs font-bold tracking-wide text-phb-gold">{{ $lang['label'] }}</span>
                <span class="flex-1 whitespace-nowrap">{{ $lang['name'] }}</span>
                @if ($locale === $lang['code'])
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-phb-green" aria-hidden="true"><path d="M5 12l5 5L20 7"/></svg>
                @endif
            </a>
        @endforeach
    </div>
</div>
