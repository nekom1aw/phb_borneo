<div class="flex flex-col gap-8">
    <x-cms.page-header :title="'Halo, ' . session('account_nama') . '!'"
                       desc="Pilih bagian website yang ingin diperbarui. Setiap isi ditulis dalam Bahasa Indonesia dan English." />

    {{-- jenis konten --}}
    <section aria-labelledby="judul-konten">
        <h2 id="judul-konten" class="mb-3 text-sm font-semibold uppercase tracking-[0.12em] text-stone-500">Mau mengisi apa hari ini?</h2>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($cards as $card)
                <div class="cms-card flex flex-col p-5">
                    <div class="flex items-start justify-between gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-phb-green text-phb-gold">
                            <x-cms.icon :name="$card['icon']" class="h-6 w-6" />
                        </span>
                        <span class="cms-badge bg-stone-100 text-stone-600">{{ $card['publish'] }} terbit</span>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-stone-900">{{ $card['nama'] }}</h3>
                    <p class="mt-1 flex-1 text-sm leading-relaxed text-stone-500">{{ $card['desc'] }}</p>
                    @if ($card['draft'])
                        <p class="mt-3 text-xs font-semibold text-amber-700">{{ $card['draft'] }} masih draft</p>
                    @endif
                    <div class="mt-5 flex gap-2">
                        <a href="{{ route('cms.' . $card['route'] . '.insert') }}" class="cms-btn-primary h-10 flex-1 px-3">
                            <x-cms.icon name="plus" class="h-4 w-4" /> Tambah
                        </a>
                        <a href="{{ route('cms.' . $card['route'] . '.index') }}" class="cms-btn h-10 px-3">Lihat semua</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- perlu dilengkapi --}}
        <section class="cms-card p-6 lg:col-span-2" aria-labelledby="judul-perlu">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 id="judul-perlu" class="text-lg font-bold text-stone-900">Perlu dilengkapi</h2>
                    <p class="text-sm text-stone-500">Konten yang belum punya versi English, belum ada foto, atau belum diterbitkan.</p>
                </div>
                @if ($perluTotal)
                    <span class="cms-badge bg-amber-100 text-amber-800">{{ $perluTotal }}</span>
                @endif
            </div>

            <ul class="mt-4 divide-y divide-stone-100">
                @forelse ($perlu as $item)
                    @php $kat = config('phb.kategori.' . $item->kategori); @endphp
                    <li class="flex items-center gap-4 py-3.5">
                        <div class="h-12 w-16 shrink-0 overflow-hidden rounded-xl bg-stone-100">
                            @if ($item->image)
                                <img src="{{ storage_url($item->image) }}" alt="" class="h-full w-full object-cover">
                            @else
                                <span class="flex h-full items-center justify-center text-stone-300"><x-cms.icon name="image" /></span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold text-stone-800">{{ $item->title_id ?: $item->title_en }}</p>
                            <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                <span class="text-xs text-stone-500">{{ $kat['nama'] }}</span>
                                @foreach ($item->alasan as $alasan)
                                    <span class="cms-badge bg-amber-50 text-amber-800">{{ $alasan }}</span>
                                @endforeach
                            </div>
                        </div>
                        <a href="{{ route('cms.' . $kat['route'] . '.edit', ['id' => $item->id]) }}" class="cms-btn h-10 shrink-0 px-3">Lengkapi</a>
                    </li>
                @empty
                    <li class="flex items-center gap-3 py-6 text-stone-600">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-600"><x-cms.icon name="check" /></span>
                        Semua konten sudah lengkap.
                    </li>
                @endforelse
            </ul>
        </section>

        <div class="flex flex-col gap-6">
            {{-- panduan --}}
            <section class="cms-card p-6" aria-labelledby="judul-panduan">
                <div class="flex items-center gap-2 text-phb-gold">
                    <x-cms.icon name="bulb" />
                    <h2 id="judul-panduan" class="font-bold text-stone-900">Cara mengisi konten</h2>
                </div>
                <ol class="mt-4 space-y-3 text-sm text-stone-600">
                    @foreach (['Pilih jenis konten lalu klik “Tambah”.', 'Pasang foto utama dan tulis isi dalam Bahasa Indonesia.', 'Buka tab English. Klik “Salin dari Bahasa Indonesia”, lalu terjemahkan.', 'Klik “Terbitkan” agar tampil di website, atau “Simpan draft” bila belum selesai.'] as $langkah)
                        <li class="flex gap-3">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-phb-green text-xs font-bold text-white">{{ $loop->iteration }}</span>
                            <span class="leading-relaxed">{{ $langkah }}</span>
                        </li>
                    @endforeach
                </ol>
            </section>

            {{-- halaman tetap --}}
            <section class="cms-card p-6" aria-labelledby="judul-halaman">
                <h2 id="judul-halaman" class="font-bold text-stone-900">Halaman tetap</h2>
                <div class="mt-3 flex flex-col gap-2">
                    <a href="{{ route('cms.beranda.index') }}" class="flex items-center gap-3 rounded-xl border border-stone-200 p-3 hover:border-phb-green/40 hover:bg-stone-50">
                        <x-cms.icon name="layout" class="text-phb-gold" />
                        <span class="flex-1 text-sm"><span class="block font-semibold text-stone-800">Beranda & Footer</span><span class="text-stone-500">Foto besar, kutipan, teks footer</span></span>
                        <x-cms.icon name="chevron-right" class="h-4 w-4 text-stone-400" />
                    </a>
                    <a href="{{ route('cms.about.index') }}" class="flex items-center gap-3 rounded-xl border border-stone-200 p-3 hover:border-phb-green/40 hover:bg-stone-50">
                        <x-cms.icon name="info" class="text-phb-gold" />
                        <span class="flex-1 text-sm"><span class="block font-semibold text-stone-800">Tentang Kami</span><span class="text-stone-500">Arti logo, visi, misi, struktur</span></span>
                        <x-cms.icon name="chevron-right" class="h-4 w-4 text-stone-400" />
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
