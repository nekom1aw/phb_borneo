@php
    $locale = app()->getLocale();

    // menu utama: posisi desktop mengikuti desain (1u = 1px @1920)
    $nav = [
        ['label' => __('Who We Are'), 'url' => route('whoweare'), 'active' => request()->routeIs('whoweare'), 'pos' => 'left-u-[313]'],
        ['label' => __('Our Work'), 'url' => route('ourwork.index'), 'active' => request()->routeIs('ourwork.*'), 'pos' => 'left-u-[627]'],
        ['label' => __('Resources'), 'url' => route('resources.index'), 'active' => request()->routeIs('resources.*'), 'pos' => 'left-u-[1098]'],
        ['label' => __('Event'), 'url' => route('event.index'), 'active' => request()->routeIs('event.*'), 'pos' => 'left-u-[1378]'],
        ['label' => __('Blog'), 'url' => route('blog.index'), 'active' => request()->routeIs('blog.*'), 'pos' => 'left-u-[1551]'],
    ];

    // URL halaman yang sama dalam bahasa lain: /en/... <-> /id/...
    $path = trim(request()->path(), '/');
    $query = request()->getQueryString();
    $langUrl = fn ($to) => url(preg_replace('#^(en|id)(?=/|$)#', $to, $path)) . ($query ? '?' . $query : '');
    $langs = [
        ['code' => 'id', 'label' => 'ID', 'name' => 'Bahasa Indonesia'],
        ['code' => 'en', 'label' => 'EN', 'name' => 'English'],
    ];
@endphp

<header class="fixed inset-x-0 top-0 z-30 transition-colors duration-300" :class="scrolled && 'bg-white/90 shadow-xs backdrop-blur-xs'">
    <div class="relative mx-auto max-w-[1920px]" :class="scrolled && 'pb-4 xl:pb-0'">

        {{-- Desktop: dua pil emas 50% + logo di tengah, switch bahasa di ujung pil kanan --}}
        <div class="hidden xl:block h-u-[127]">
            <div class="relative h-u-[127]">
                <div class="absolute left-u-[141] top-u-[36] h-u-[55] w-u-[760] rounded-l-full bg-phb-gold/50 transition-colors duration-300" :class="scrolled && 'bg-phb-gold!'"></div>
                <div class="absolute left-u-[1019] top-u-[36] h-u-[55] w-u-[760] rounded-r-full bg-phb-gold/50 transition-colors duration-300" :class="scrolled && 'bg-phb-gold!'"></div>

                <a href="{{ route('index') }}" class="absolute left-u-[913] top-u-[12] w-u-[94] h-u-[103] text-phb-green" aria-label="{{ config('app.name') }}">
                    @include('components.user.logo')
                </a>

                <nav class="text-white uppercase text-[max(16px,calc(22*var(--u)))] leading-none" aria-label="{{ __('Main menu') }}">
                    @foreach ($nav as $item)
                        <a href="{{ $item['url'] }}" @if ($item['active']) aria-current="page" @endif
                           class="absolute {{ $item['pos'] }} top-u-[36] h-u-[55] flex items-center whitespace-nowrap hover:opacity-80">{{ $item['label'] }}</a>
                    @endforeach
                </nav>

                <div class="absolute right-u-[176] top-u-[36] h-u-[55] flex items-center">
                    <span class="mr-u-[12] h-u-[25] w-px bg-white/55" aria-hidden="true"></span>
                    @include('components.user.lang-dropdown', ['size' => 'desktop'])
                </div>
            </div>
        </div>

        {{-- Mobile: dua pil emas dengan logo di tengah; menu di pil kiri, bahasa di pil kanan --}}
        <div class="xl:hidden relative mx-4 mt-4 md:mx-10 md:mt-6 flex items-center gap-[84px] md:gap-[96px]">
            <div class="h-11 flex-1 rounded-l-full bg-phb-gold/50 transition-colors duration-300" :class="scrolled && 'bg-phb-gold!'"></div>
            <div class="h-11 flex-1 rounded-r-full bg-phb-gold/50 transition-colors duration-300" :class="scrolled && 'bg-phb-gold!'"></div>

            <a href="{{ route('index') }}" class="absolute left-1/2 top-1/2 w-[50px] -translate-x-1/2 -translate-y-1/2 text-phb-green" aria-label="{{ config('app.name') }}">
                @include('components.user.logo')
            </a>

            <button type="button" class="absolute left-1 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-white outline-hidden focus-visible:ring-2 focus-visible:ring-white/70"
                    @click="open = true" :aria-expanded="open" aria-label="{{ __('Open menu') }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="h-6 w-6" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>

            <div class="absolute right-2 top-1/2 -translate-y-1/2">
                @include('components.user.lang-dropdown', ['size' => 'mobile'])
            </div>
        </div>
    </div>
</header>

{{-- Menu samping mobile: meluncur dari kiri, setinggi layar --}}
<div x-cloak x-show="open" x-transition.opacity.duration.300ms @click="open = false"
     class="xl:hidden fixed inset-0 z-40 bg-black/50"></div>
<aside x-cloak x-show="open" @keydown.escape.window="open = false"
       x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
       class="xl:hidden fixed inset-y-0 left-0 z-50 flex w-[82%] max-w-xs flex-col bg-phb-green text-white shadow-2xl">
    <div class="flex items-center justify-between px-6 pb-4 pt-6">
        <a href="{{ route('index') }}" class="block w-12" aria-label="{{ config('app.name') }}">
            @include('components.user.logo')
        </a>
        <button type="button" class="flex h-10 w-10 items-center justify-center rounded-full text-white/80 outline-hidden hover:bg-white/10 focus-visible:ring-2 focus-visible:ring-white/70"
                @click="open = false" aria-label="{{ __('Close menu') }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="h-6 w-6" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </div>
    <nav class="mt-4 flex flex-col" aria-label="{{ __('Main menu') }}">
        @foreach ($nav as $item)
            <a href="{{ $item['url'] }}" @if ($item['active']) aria-current="page" @endif
               class="border-b border-white/10 px-6 py-4 text-sm uppercase tracking-wide hover:bg-white/5 {{ $item['active'] ? 'font-bold text-phb-gold' : '' }}">{{ $item['label'] }}</a>
        @endforeach
    </nav>
    <p class="mt-auto px-6 pb-8 text-xs text-white/60">Copyright © {{ date('Y') }} {{ config('app.name') }}</p>
</aside>
