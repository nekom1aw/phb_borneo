<div>
    <x-user.hero :src="storage_url($setting?->hero_page)" />

    {{-- Arti Logo / Visi / Misi --}}
    <main class="mx-auto max-w-[1920px] px-5 py-10 md:px-10 md:py-14 xl:p-0 xl:pl-u-[313] xl:mt-u-[119.7]">
        @foreach ([['logo', __('Arti Logo'), 'xl:mt-0'], ['visi', __('Visi'), 'mt-10 xl:mt-u-[127]'], ['misi', __('Misi'), 'mt-10 xl:mt-u-[115.7]']] as [$key, $label, $space])
            <section x-data="reveal(0)" x-intersect.once="show()" :class="shown && 'opacity-100! translate-y-0!'" class="{{ $space }} opacity-0 translate-y-10 transition-all duration-700 ease-out motion-reduce:transition-none">
                <x-user.heading>{{ $label }}</x-user.heading>
                <h3 class="mt-4 font-bold text-lg leading-snug xl:mt-u-[58.25] xl:text-u-[25] xl:leading-u-[26.4]">{{ tr($about, $key . '_title') }}</h3>
                <div class="phb-content mt-2 text-base leading-relaxed xl:mt-u-[19.3] xl:w-u-[958] xl:text-u-[22] xl:leading-u-[31]">{!! tr($about, $key . '_content') !!}</div>
            </section>
        @endforeach

        {{-- Struktur kepengurusan --}}
        @if ($about?->struktur_image)
            <figure x-data="reveal(0)" x-intersect.once="show()" :class="shown && 'opacity-100! translate-y-0!'" class="mt-10 xl:mt-u-[113.1] xl:w-u-[1356] opacity-0 translate-y-10 transition-all duration-700 ease-out motion-reduce:transition-none">
                <img src="{{ storage_url($about->struktur_image) }}" alt="{{ __('Structure of Prasasti Hijau Borneo Foundation') }}"
                     class="block w-full h-auto cursor-zoom-in" @click="lightbox = $el.src">
            </figure>
        @endif
    </main>
</div>
