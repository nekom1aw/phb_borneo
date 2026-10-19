@php
    $listUrl = route('cms.' . $info['route'] . '.index');
    $fotoPreview = ($image && ! $errors->has('image')) ? $image->temporaryUrl() : storage_url($oldImage);
    $terbit = $status === 'publish';
@endphp

<div x-data="cmsForm(['title', 'deskripsi', 'content'])" @form-invalid.window="onInvalid($event.detail.fields); dirty = true" class="flex flex-col gap-6">
    <x-cms.page-header :title="($kontenId ? 'Ubah ' : 'Tambah ') . $info['nama']" :desc="$info['desc']"
                       :back="$listUrl" :back-label="'Kembali ke daftar ' . $info['nama']" />

    @if ($errors->any())
        <div class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            <x-cms.icon name="alert" class="mt-0.5 h-5 w-5" />
            <p><span class="font-semibold">Belum bisa disimpan.</span> Periksa isian yang bertanda merah di bawah.</p>
        </div>
    @endif

    <form @submit.prevent class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">
        <div class="flex min-w-0 flex-col gap-6">

            {{-- 1. foto --}}
            <section class="cms-card p-5 md:p-6">
                <div class="mb-4 flex items-start gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-phb-green text-sm font-bold text-white">1</span>
                    <div>
                        <h2 class="text-lg font-bold text-stone-900">Foto utama</h2>
                        <p class="text-sm text-stone-500">Tampil di kartu daftar dan di bagian atas halaman detail.</p>
                    </div>
                </div>
                <x-cms.image-upload model="image" :preview="$fotoPreview" aspect="aspect-[16/9]" hint="JPG, PNG, atau WebP. Maks 4 MB. Foto mendatar (landscape) paling pas." />
                @error('image') <p data-error class="mt-2 flex items-center gap-1.5 text-sm font-medium text-red-600"><x-cms.icon name="alert" class="h-4 w-4" /> {{ $message }}</p> @enderror
            </section>

            {{-- 2. tulisan dua bahasa --}}
            <section class="cms-card p-5 md:p-6">
                <div class="mb-4 flex items-start gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-phb-green text-sm font-bold text-white">2</span>
                    <div>
                        <h2 class="text-lg font-bold text-stone-900">Tulisan</h2>
                        <p class="text-sm text-stone-500">Tulis dulu dalam Bahasa Indonesia, lalu lengkapi versi English. Titik hijau berarti sudah lengkap.</p>
                    </div>
                </div>

                <x-cms.lang-tabs />

                @foreach (['id' => 'Bahasa Indonesia', 'en' => 'English'] as $lang => $nama)
                    <div x-show="tab === '{{ $lang }}'" @if ($lang !== 'id') x-cloak @endif class="mt-6 flex flex-col gap-6" role="tabpanel">
                        @if ($lang === 'en')
                            <div class="flex flex-col gap-3 rounded-2xl bg-phb-gold/10 p-4 sm:flex-row sm:items-center">
                                <x-cms.icon name="bulb" class="hidden h-6 w-6 text-phb-gold sm:block" />
                                <p class="flex-1 text-sm text-stone-700">Belum sempat menerjemahkan? Salin teks Bahasa Indonesia ke sini, lalu ubah ke English. Bila English kosong, pengunjung akan melihat versi Bahasa Indonesia.</p>
                                <button type="button" class="cms-btn h-10 shrink-0" @click="copyFromId('Isi English yang sudah ada akan diganti dengan teks Bahasa Indonesia. Lanjutkan?')">
                                    <x-cms.icon name="copy" class="h-4 w-4" /> Salin dari Bahasa Indonesia
                                </button>
                            </div>
                        @endif

                        <x-cms.field label="Judul" for="title_{{ $lang }}" :required="$lang === 'id'" :optional="$lang === 'en'"
                                     counter="title_{{ $lang }}" max="120" :error="$errors->first('title_' . $lang)"
                                     hint="Tampil sebagai judul kartu dan judul halaman. Usahakan singkat dan jelas.">
                            <input id="title_{{ $lang }}" type="text" wire:model="title_{{ $lang }}" class="cms-input text-base font-semibold"
                                   placeholder="{{ $lang === 'id' ? 'Contoh: Pelatihan Patroli Hutan Desa 2026' : 'Example: Village Forest Patrol Training 2026' }}"
                                   @error('title_' . $lang) aria-invalid="true" @enderror>
                        </x-cms.field>

                        <x-cms.field label="Ringkasan" for="deskripsi_{{ $lang }}" optional counter="deskripsi_{{ $lang }}" max="300"
                                     :error="$errors->first('deskripsi_' . $lang)"
                                     hint="1–2 kalimat. Tampil di bawah judul pada kartu dan di halaman detail.">
                            <textarea id="deskripsi_{{ $lang }}" rows="3" wire:model="deskripsi_{{ $lang }}" class="cms-input resize-y"
                                      placeholder="{{ $lang === 'id' ? 'Ceritakan intinya dalam satu atau dua kalimat.' : 'Summarise it in one or two sentences.' }}"
                                      @error('deskripsi_' . $lang) aria-invalid="true" @enderror></textarea>
                        </x-cms.field>

                        <x-cms.field label="Isi lengkap" optional :error="$errors->first('content_' . $lang)"
                                     hint="Gunakan tombol di atas kotak untuk subjudul, daftar, tautan, atau menambah foto di tengah tulisan.">
                            <x-tinymce model="content_{{ $lang }}" :placeholder="$lang === 'id' ? 'Tulis isi lengkap di sini…' : 'Write the full text here…'" />
                        </x-cms.field>
                    </div>
                @endforeach
            </section>

            {{-- 3. detail --}}
            <section class="cms-card p-5 md:p-6">
                <div class="mb-4 flex items-start gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-phb-green text-sm font-bold text-white">3</span>
                    <div>
                        <h2 class="text-lg font-bold text-stone-900">Detail</h2>
                        <p class="text-sm text-stone-500">Konten di website diurutkan dari tanggal terbaru.</p>
                    </div>
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    <x-cms.field :label="$kategori === 'event' ? 'Tanggal acara' : 'Tanggal'" for="tanggal" required :error="$errors->first('tanggal')"
                                 :hint="$kategori === 'event' ? 'Hari acara berlangsung.' : 'Biasanya tanggal hari ini.'">
                        <input id="tanggal" type="date" wire:model="tanggal" class="cms-input" @error('tanggal') aria-invalid="true" @enderror>
                    </x-cms.field>

                    @if ($kategori === 'resources')
                        <x-cms.field label="File PDF" optional :error="$errors->first('file')" hint="Pengunjung bisa mengunduhnya dari halaman detail. Maks 20 MB.">
                            @if ($oldFile && ! $file)
                                <div class="flex items-center gap-3 rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5">
                                    <x-cms.icon name="file" class="text-red-600" />
                                    <a href="{{ storage_url($oldFile) }}" target="_blank" rel="noopener" class="min-w-0 flex-1 truncate text-sm font-medium text-stone-800 underline-offset-2 hover:underline">{{ basename($oldFile) }}</a>
                                    <button type="button" wire:click="removeFile" wire:confirm="Hapus file PDF ini?" class="text-sm font-semibold text-red-600 hover:underline">Hapus</button>
                                </div>
                            @endif
                            <label for="file" class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-dashed border-stone-300 bg-stone-50 px-4 py-3 text-sm hover:border-phb-green/60 hover:bg-phb-green/5">
                                <x-cms.icon name="upload" class="text-phb-green" />
                                <span class="min-w-0 flex-1 truncate text-stone-700">
                                    @if ($file && ! $errors->has('file'))
                                        {{ $file->getClientOriginalName() }}
                                    @else
                                        {{ $oldFile ? 'Ganti dengan file PDF lain' : 'Pilih file PDF' }}
                                    @endif
                                </span>
                                <span wire:loading wire:target="file" class="text-xs text-phb-green">Mengunggah…</span>
                            </label>
                            <input id="file" type="file" wire:model="file" accept="application/pdf" class="sr-only">
                        </x-cms.field>
                    @endif
                </div>
            </section>
        </div>

        {{-- panel terbitkan --}}
        <aside class="flex flex-col gap-4 lg:sticky lg:top-24">
            <div class="cms-card p-5">
                <p class="text-sm font-semibold text-stone-500">Status</p>
                <div class="mt-2 flex items-start gap-3">
                    @if ($kontenId && $terbit)
                        <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-emerald-500"></span>
                        <p class="text-sm text-stone-700"><span class="font-semibold text-stone-900">Terbit.</span> Sudah tampil di website.</p>
                    @else
                        <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-amber-400"></span>
                        <p class="text-sm text-stone-700"><span class="font-semibold text-stone-900">{{ $kontenId ? 'Draft.' : 'Belum disimpan.' }}</span> Belum tampil di website.</p>
                    @endif
                </div>

                <div class="mt-5 flex flex-col gap-2">
                    <button type="button" wire:click="save('publish')" @click="dirty = false" class="cms-btn-primary h-12 w-full text-[15px]"
                            wire:loading.attr="disabled" wire:target="save, image, file">
                        <x-cms.icon name="send" class="h-4 w-4" />
                        <span wire:loading.remove wire:target="save">{{ $kontenId && $terbit ? 'Simpan perubahan' : 'Terbitkan' }}</span>
                        <span wire:loading wire:target="save">Menyimpan…</span>
                    </button>
                    <button type="button" wire:click="save('draft')" @click="dirty = false" class="cms-btn w-full"
                            wire:loading.attr="disabled" wire:target="save, image, file">
                        <x-cms.icon name="save" class="h-4 w-4" />
                        {{ $kontenId && $terbit ? 'Sembunyikan (jadikan draft)' : 'Simpan sebagai draft' }}
                    </button>
                    @if ($kontenId)
                        <a href="{{ konten_url((object) ['kategori' => $kategori, 'id' => $kontenId, 'slug' => null]) }}" target="_blank" rel="noopener" class="cms-btn-ghost w-full">
                            <x-cms.icon name="eye" class="h-4 w-4" /> {{ $terbit ? 'Lihat di website' : 'Pratinjau' }}
                        </a>
                    @endif
                </div>

                <p class="mt-4 text-xs leading-relaxed text-stone-500">
                    @if ($updatedAt)
                        Terakhir disimpan {{ \Carbon\Carbon::parse($updatedAt)->locale('id')->diffForHumans() }}.
                    @endif
                    <span x-show="dirty" class="font-semibold text-amber-700">Ada perubahan yang belum disimpan.</span>
                </p>
            </div>

            {{-- kelengkapan --}}
            <div class="cms-card p-5">
                <p class="text-sm font-semibold text-stone-500">Kelengkapan</p>
                <ul class="mt-3 space-y-2.5 text-sm">
                    <li class="flex items-center gap-2.5">
                        @if ($image || $oldImage)
                            <x-cms.icon name="check-circle" class="text-emerald-600" />
                        @else
                            <x-cms.icon name="circle" class="text-stone-300" />
                        @endif
                        Foto utama
                    </li>
                    @foreach (['id' => 'Bahasa Indonesia', 'en' => 'English'] as $lang => $nama)
                        @foreach (['title' => 'Judul', 'content' => 'Isi lengkap'] as $field => $label)
                            <li class="flex items-center gap-2.5">
                                <span x-show="filled($wire.{{ $field }}_{{ $lang }})"><x-cms.icon name="check-circle" class="text-emerald-600" /></span>
                                <span x-show="!filled($wire.{{ $field }}_{{ $lang }})"><x-cms.icon name="circle" class="text-stone-300" /></span>
                                {{ $label }} <span class="text-stone-400">· {{ $nama }}</span>
                            </li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </aside>
    </form>
</div>
