{{-- Kartu konten. size: small (Resources/Event di Beranda), large (Galery di Beranda), row (halaman daftar) --}}
@props(['item', 'size' => 'small', 'delay' => 0])

@php
    $title = tr($item, 'title');
    $text = tr($item, 'deskripsi');
    $url = konten_url($item);
    $image = storage_url($item->image);
@endphp

@if ($size === 'row')
    <article {{ $attributes }} x-data="reveal({{ $delay }})" x-intersect.once="show()" :class="shown && 'opacity-100! translate-y-0!'" class="flex flex-col gap-4 sm:flex-row sm:gap-6 xl:gap-0 opacity-0 translate-y-10 transition-all duration-700 ease-out motion-reduce:transition-none">
        <a href="{{ $url }}" class="block shrink-0 w-full overflow-hidden aspect-[331/244] rounded-[20px] border border-phb-gold sm:w-56 xl:w-u-[331] xl:rounded-u-[24] xl:border-u-[1.5]">
            @if ($image)
                <img src="{{ $image }}" alt="{{ $title }}" loading="lazy" class="block h-full w-full object-cover">
            @endif
        </a>
        <div class="xl:ml-u-[65] xl:pt-u-[73.5] xl:w-u-[820]">
            <h3 class="font-bold text-lg leading-snug xl:text-u-[25] xl:leading-u-[26.4]"><a href="{{ $url }}" class="hover:opacity-80">{{ $title }}</a></h3>
            <p class="mt-2 text-base leading-relaxed xl:mt-u-[19.6] xl:text-u-[22] xl:leading-u-[31]">{{ $text }}</p>
        </div>
    </article>
@elseif ($size === 'large')
    <article {{ $attributes }} x-data="reveal({{ $delay }})" x-intersect.once="show()" :class="shown && 'opacity-100! translate-y-0!'" class="xl:w-u-[788] opacity-0 translate-y-10 transition-all duration-700 ease-out motion-reduce:transition-none">
        <a href="{{ $url }}" class="block overflow-hidden aspect-[788/580] rounded-[32px] border-[3px] border-phb-gold xl:rounded-u-[57] xl:border-u-[3]">
            @if ($image)
                <img src="{{ $image }}" alt="{{ $title }}" loading="lazy" class="block h-full w-full object-cover">
            @endif
        </a>
        <div class="px-4 xl:px-0 xl:pl-u-[35] xl:w-u-[753]">
            <h3 class="mt-5 font-bold text-lg leading-snug xl:mt-u-[33.1] xl:text-u-[25] xl:leading-u-[26.4]"><a href="{{ $url }}" class="hover:opacity-80">{{ $title }}</a></h3>
            <p class="mt-2 text-base leading-relaxed xl:mt-u-[19.3] xl:text-u-[22] xl:leading-u-[31]">{{ $text }}</p>
        </div>
    </article>
@else
    <article {{ $attributes }} x-data="reveal({{ $delay }})" x-intersect.once="show()" :class="shown && 'opacity-100! translate-y-0!'" class="xl:w-u-[495.5] opacity-0 translate-y-10 transition-all duration-700 ease-out motion-reduce:transition-none">
        <a href="{{ $url }}" class="block overflow-hidden aspect-[496/365] rounded-[28px] border-2 border-phb-gold xl:rounded-u-[36] xl:border-u-[2]">
            @if ($image)
                <img src="{{ $image }}" alt="{{ $title }}" loading="lazy" class="block h-full w-full object-cover">
            @endif
        </a>
        <div class="px-4 xl:px-0 xl:pl-u-[35] xl:w-u-[471]">
            <h3 class="mt-5 font-bold text-lg leading-snug xl:mt-u-[43.4] xl:text-u-[25] xl:leading-u-[26.4]"><a href="{{ $url }}" class="hover:opacity-80">{{ $title }}</a></h3>
            <p class="mt-2 text-base leading-relaxed xl:mt-u-[19.3] xl:text-u-[22] xl:leading-u-[31]">{{ $text }}</p>
        </div>
    </article>
@endif
