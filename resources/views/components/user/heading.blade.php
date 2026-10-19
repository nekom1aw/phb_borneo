{{-- Judul seksi (huruf kapital, muncul dari bawah) --}}
<h2 x-data="reveal(0)" x-intersect.once="show()" :class="shown && 'opacity-100! translate-y-0!'" {{ $attributes->merge(['class' => 'font-bold uppercase text-xl leading-none xl:text-u-[29.4] opacity-0 translate-y-10 transition-all duration-700 ease-out motion-reduce:transition-none']) }}>{{ $slot }}</h2>
