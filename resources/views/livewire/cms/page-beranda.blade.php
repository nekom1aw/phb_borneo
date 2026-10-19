@php
    $heroHome = ($hero_home && ! $errors->has('hero_home')) ? $hero_home->temporaryUrl() : storage_url($oldHeroHome);
    $heroPage = ($hero_page && ! $errors->has('hero_page')) ? $hero_page->temporaryUrl() : storage_url($oldHeroPage);
@endphp

<div x-data="cmsForm(['quote', 'footer'])" @form-invalid.window="onInvalid($event.detail.fields); dirty = true" class="flex flex-col gap-6">
    <x-cms.page-header title="Beranda & Footer" icon="layout"
                       desc="Atur foto besar dan kutipan di halaman depan, serta teks di bagian paling bawah (footer) semua halaman." />

    <x-cms.step number="1" title="Foto besar (hero)" desc="Foto yang memenuhi bagian atas halaman. Gunakan foto mendatar berukuran besar, minimal lebar 1920 piksel.">
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <p class="cms-label mb-2">Foto halaman Beranda</p>
                <x-cms.image-upload model="hero_home" :preview="$heroHome" aspect="aspect-[16/10]" hint="Tampil penuh di halaman depan. Maks 8 MB." />
                @error('hero_home') <p data-error class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <p class="cms-label mb-2">Foto halaman lain</p>
                <x-cms.image-upload model="hero_page" :preview="$heroPage" aspect="aspect-[16/10]" hint="Tampil di atas halaman Who We Are, Resources, Event, dll. Maks 8 MB." />
                @error('hero_page') <p data-error class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </x-cms.step>

    <x-cms.step number="2" title="Teks" desc="Isi untuk Bahasa Indonesia dan English. Titik hijau berarti sudah lengkap.">
        <x-cms.lang-tabs />

        @foreach (['id', 'en'] as $lang)
            <div x-show="tab === '{{ $lang }}'" @if ($lang !== 'id') x-cloak @endif class="mt-6 flex flex-col gap-8" role="tabpanel">
                @if ($lang === 'en')
                    <x-cms.copy-hint />
                @endif

                <div class="grid gap-6 lg:grid-cols-2">
                    <x-cms.field label="Kutipan di foto Beranda" for="quote_{{ $lang }}" required counter="quote_{{ $lang }}" max="60"
                                 :error="$errors->first('quote_' . $lang)" hint="Tekan Enter untuk pindah baris. Kalimat pendek terlihat paling bagus.">
                        <textarea id="quote_{{ $lang }}" rows="3" wire:model="quote_{{ $lang }}" class="cms-input resize-none text-base" @error('quote_' . $lang) aria-invalid="true" @enderror></textarea>
                    </x-cms.field>

                    {{-- pratinjau kutipan --}}
                    <div>
                        <p class="cms-label mb-2">Pratinjau</p>
                        <div class="relative aspect-[16/9] overflow-hidden rounded-2xl bg-phb-green">
                            @if ($heroHome)
                                <img src="{{ $heroHome }}" alt="" class="absolute inset-0 h-full w-full object-cover">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            <div class="absolute bottom-[12%] left-[7%] flex items-start gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 84 64" fill="currentColor" aria-hidden="true" class="mt-0.5 h-auto w-5 shrink-0 text-phb-quote"><path d="M40 0C40 16 38 28 34.9 37.8A18 18 0 1 1 22.7 26.6C28 16 34 8 40 0Z"/><path transform="translate(44 0)" d="M40 0C40 16 38 28 34.9 37.8A18 18 0 1 1 22.7 26.6C28 16 34 8 40 0Z"/></svg>
                                <p class="whitespace-pre-line text-lg leading-tight text-white md:text-xl" x-text="$wire.quote_{{ $lang }} || 'Tulis kutipan…'"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <x-cms.field label="Teks footer" for="footer_{{ $lang }}" optional counter="footer_{{ $lang }}" max="250"
                             :error="$errors->first('footer_' . $lang)" hint="Tampil di bawah nama yayasan pada bagian paling bawah setiap halaman.">
                    <textarea id="footer_{{ $lang }}" rows="3" wire:model="footer_{{ $lang }}" class="cms-input resize-y" @error('footer_' . $lang) aria-invalid="true" @enderror></textarea>
                </x-cms.field>
            </div>
        @endforeach
    </x-cms.step>

    <x-cms.save-bar wire:target="save, hero_home, hero_page" />
</div>
