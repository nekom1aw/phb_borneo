<div>
    {{-- Hero menempel di atas layar sementara konten naik menutupinya; lama hero diam = tinggi spacer --}}
    <div>
        <div class="sticky top-0 z-0">
            <section class="relative">
                <img src="{{ storage_url($setting?->hero_home) ?: asset('storage/seed/hero-home.jpg') }}" alt="Hamparan hutan Kalimantan berkabut saat fajar"
                     class="block w-full h-[70vh] object-cover object-center xl:h-auto">
                <div x-data="parallax(0.6)" @scroll.window.passive="onScroll()" :style="style"
                     class="absolute left-5 right-5 bottom-10 flex items-start gap-2 will-change-transform xl:inset-auto xl:left-u-[170] xl:top-u-[829] xl:gap-u-[4]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 84 64" fill="currentColor" aria-hidden="true" class="shrink-0 w-7 h-auto mt-1 xl:mt-0 xl:w-u-[42] xl:h-u-[32] text-phb-quote">
                        <path d="M40 0C40 16 38 28 34.9 37.8A18 18 0 1 1 22.7 26.6C28 16 34 8 40 0Z"/>
                        <path transform="translate(44 0)" d="M40 0C40 16 38 28 34.9 37.8A18 18 0 1 1 22.7 26.6C28 16 34 8 40 0Z"/>
                    </svg>
                    <p class="text-white text-2xl leading-tight xl:text-u-[52.6] xl:leading-u-[63]">{!! nl2br(e(tr($setting, 'quote'))) !!}</p>
                </div>
            </section>
        </div>
        <div class="h-[30vh] xl:h-u-[574]" aria-hidden="true"></div>
    </div>

    {{-- Lapisan konten yang menutupi hero --}}
    <div class="relative z-10 flow-root bg-white -mt-[30vh] xl:mt-[calc(-574*var(--u))]">
        <main class="mx-auto max-w-[1920px] px-5 py-10 md:px-10 md:py-14 xl:p-0">
            <section class="flex flex-col gap-10 xl:flex-row xl:gap-u-[76] xl:mt-u-[76.7] xl:pl-u-[141]">

                {{-- Resources (2 kartu terbaru) --}}
                <div>
                    <x-user.heading><a href="{{ route('resources.index') }}" class="hover:opacity-80">{{ __('Resources') }}</a></x-user.heading>
                    <div class="mt-5 grid gap-8 sm:grid-cols-2 xl:mt-u-[34.7] xl:flex xl:gap-u-[75.5]">
                        @forelse ($resources as $item)
                            <x-user.card :item="$item" :delay="$loop->index * 120" wire:key="resources-{{ $item->id }}" />
                        @empty
                            <p class="text-base opacity-70">{{ __('No content yet.') }}</p>
                        @endforelse
                    </div>
                </div>

                {{-- Event (1 kartu terbaru) --}}
                <div>
                    <x-user.heading><a href="{{ route('event.index') }}" class="hover:opacity-80">{{ __('Event') }}</a></x-user.heading>
                    <div class="mt-5 grid gap-8 sm:grid-cols-2 xl:mt-u-[34.7] xl:flex xl:gap-u-[75.5]">
                        @forelse ($events as $item)
                            <x-user.card :item="$item" :delay="$loop->index * 120" wire:key="event-{{ $item->id }}" />
                        @empty
                            <p class="text-base opacity-70">{{ __('No content yet.') }}</p>
                        @endforelse
                    </div>
                </div>
            </section>

            {{-- Galery (2 foto terbaru dari Our Work) --}}
            <section class="mt-10 xl:mt-u-[85.4] xl:pl-u-[141]">
                <x-user.heading><a href="{{ route('ourwork.index') }}" class="hover:opacity-80">{{ __('Galery') }}</a></x-user.heading>
                <div class="mt-5 grid gap-8 md:grid-cols-2 xl:mt-u-[35.7] xl:flex xl:gap-u-[62]">
                    @forelse ($photos as $item)
                        <x-user.card :item="$item" size="large" :delay="$loop->index * 120" wire:key="photo-{{ $item->id }}" />
                    @empty
                        <p class="text-base opacity-70">{{ __('No content yet.') }}</p>
                    @endforelse
                </div>
            </section>
        </main>
    </div>
</div>
