<div>
    @if ($item->status !== 'publish')
        <div class="fixed inset-x-0 bottom-0 z-40 flex flex-wrap items-center justify-center gap-x-4 gap-y-1 bg-amber-400 px-4 py-3 text-center text-sm font-semibold text-stone-900">
            Pratinjau: konten ini masih draft dan belum terlihat oleh pengunjung.
            <a href="{{ route('cms.' . config('phb.kategori.' . $kategori . '.route') . '.edit', ['locale' => 'id', 'id' => $item->id]) }}" class="underline underline-offset-2">Kembali ke CMS</a>
        </div>
    @endif

    <x-user.hero :src="storage_url($setting?->hero_page)" />

    <main class="mx-auto max-w-[1920px] px-5 py-10 md:px-10 md:py-14 xl:p-0 xl:pl-u-[140] xl:mt-u-[100.6]">
        <x-user.heading><a href="{{ route(config('phb.kategori.' . $kategori . '.route') . '.index') }}" class="hover:opacity-80">{{ __($heading) }}</a></x-user.heading>

        <h1 x-data="reveal(100)" x-intersect.once="show()" :class="shown && 'opacity-100! translate-y-0!'" class="mt-3 font-bold text-2xl leading-tight xl:mt-u-[40.5] xl:text-u-[34.2] xl:leading-none opacity-0 translate-y-10 transition-all duration-700 ease-out motion-reduce:transition-none">{{ tr($item, 'title') }}</h1>

        @if (tr($item, 'deskripsi'))
            <p x-data="reveal(200)" x-intersect.once="show()" :class="shown && 'opacity-100! translate-y-0!'" class="mt-2 text-lg leading-relaxed xl:mt-u-[27.6] xl:w-u-[1110] xl:text-u-[30.1] xl:leading-u-[42] opacity-0 translate-y-10 transition-all duration-700 ease-out motion-reduce:transition-none">{{ tr($item, 'deskripsi') }}</p>
        @endif

        @if ($item->image)
            <figure x-data="reveal(0)" x-intersect.once="show()" :class="shown && 'opacity-100! translate-y-0!'" class="mt-6 xl:mt-u-[66.4] xl:ml-u-[4] xl:w-u-[1086] opacity-0 translate-y-10 transition-all duration-700 ease-out motion-reduce:transition-none">
                <img src="{{ storage_url($item->image) }}" alt="{{ tr($item, 'title') }}"
                     class="block w-full aspect-video object-cover cursor-zoom-in" @click="lightbox = $el.src">
            </figure>
        @endif

        <div x-data="reveal(0)" x-intersect.once="show()" :class="shown && 'opacity-100! translate-y-0!'" class="phb-content mt-6 text-base leading-relaxed xl:mt-u-[80.4] xl:ml-u-[15] xl:w-u-[740] xl:text-u-[22] xl:leading-u-[31] opacity-0 translate-y-10 transition-all duration-700 ease-out motion-reduce:transition-none">
            {!! tr($item, 'content') !!}
        </div>

        @if ($item->file)
            <a href="{{ storage_url($item->file) }}" target="_blank" rel="noopener"
               class="mt-8 inline-flex items-center gap-3 rounded-full bg-phb-green px-6 py-3 text-sm font-bold uppercase tracking-wide text-white hover:bg-phb-green/90 xl:mt-u-[60] xl:ml-u-[15] xl:text-[16px]">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"/></svg>
                {{ __('Download file') }}
            </a>
        @endif
    </main>
</div>
