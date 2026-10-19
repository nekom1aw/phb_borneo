@php
    $struktur = ($struktur_image && ! $errors->has('struktur_image')) ? $struktur_image->temporaryUrl() : storage_url($oldStruktur);
    $bagian = [
        'logo' => ['Arti Logo', 'Makna logo Prasasti Hijau Borneo.'],
        'visi' => ['Visi', 'Cita-cita jangka panjang yayasan.'],
        'misi' => ['Misi', 'Langkah-langkah untuk mencapai visi.'],
    ];
@endphp

<div x-data="cmsForm(['logo_title', 'logo_content', 'visi_title', 'visi_content', 'misi_title', 'misi_content'])"
     @form-invalid.window="onInvalid($event.detail.fields); dirty = true" class="flex flex-col gap-6">
    <x-cms.page-header title="Tentang Kami" icon="info"
                       desc="Isi halaman Who We Are: arti logo, visi, misi, dan gambar struktur kepengurusan." />

    <x-cms.step number="1" title="Tulisan" desc="Setiap bagian punya subjudul dan isi. Titik hijau di tab berarti semua bagian sudah terisi.">
        <x-cms.lang-tabs />

        @foreach (['id', 'en'] as $lang)
            <div x-show="tab === '{{ $lang }}'" @if ($lang !== 'id') x-cloak @endif class="mt-6 flex flex-col gap-6" role="tabpanel">
                @if ($lang === 'en')
                    <x-cms.copy-hint />
                @endif

                @foreach ($bagian as $key => [$judul, $desc])
                    <div class="rounded-2xl border border-stone-200 p-4 md:p-5">
                        <div class="mb-4">
                            <h3 class="font-bold text-stone-900">{{ $judul }}</h3>
                            <p class="text-sm text-stone-500">{{ $desc }}</p>
                        </div>
                        <div class="flex flex-col gap-5">
                            <x-cms.field label="Subjudul" for="{{ $key }}_title_{{ $lang }}" optional :error="$errors->first($key . '_title_' . $lang)">
                                <input id="{{ $key }}_title_{{ $lang }}" type="text" wire:model="{{ $key }}_title_{{ $lang }}" class="cms-input font-semibold">
                            </x-cms.field>
                            <x-cms.field label="Isi" optional>
                                <x-tinymce model="{{ $key }}_content_{{ $lang }}" height="260" />
                            </x-cms.field>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </x-cms.step>

    <x-cms.step number="2" title="Struktur kepengurusan" desc="Gambar bagan pengurus. Pengunjung bisa mengekliknya untuk memperbesar.">
        <div class="max-w-2xl">
            <x-cms.image-upload model="struktur_image" :preview="$struktur" aspect="aspect-[4/3]" fit="object-contain bg-white" hint="JPG atau PNG. Maks 8 MB." />
            @error('struktur_image') <p data-error class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
        </div>
    </x-cms.step>

    <x-cms.save-bar wire:target="save, struktur_image" />
</div>
