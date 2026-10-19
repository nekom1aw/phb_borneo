@php
    $isi = fn ($row, $lang) => trim((string) $row->{'title_' . $lang}) !== '' && trim(strip_tags((string) $row->{'content_' . $lang})) !== '';
@endphp

<div class="flex flex-col gap-6">
    <x-cms.page-header :title="$info['nama']" :desc="$info['desc']" :icon="$info['icon']">
        <a href="{{ route('cms.' . $info['route'] . '.insert') }}" class="cms-btn-primary">
            <x-cms.icon name="plus" class="h-4 w-4" /> Tambah {{ $info['nama'] }}
        </a>
    </x-cms.page-header>

    {{-- filter & cari --}}
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex gap-1 rounded-2xl bg-stone-200/60 p-1" role="tablist" aria-label="Saring status">
            @foreach (['' => 'Semua', 'publish' => 'Terbit', 'draft' => 'Draft'] as $value => $label)
                <button type="button" role="tab" wire:click="$set('status', '{{ $value }}')" aria-selected="{{ $status === $value ? 'true' : 'false' }}"
                        class="flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition {{ $status === $value ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-500 hover:text-stone-800' }}">
                    {{ $label }}
                    <span class="rounded-full px-1.5 text-xs {{ $status === $value ? 'bg-stone-100 text-stone-600' : 'bg-stone-300/50 text-stone-500' }}">{{ $jumlah[$value] }}</span>
                </button>
            @endforeach
        </div>
        <div class="relative md:w-80">
            <label for="search" class="sr-only">Cari judul</label>
            <x-cms.icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400" />
            <input id="search" type="search" wire:model.live.debounce.400ms="search" placeholder="Cari judul…" class="cms-input pl-11">
        </div>
    </div>

    {{-- daftar --}}
    <div class="cms-card divide-y divide-stone-100 overflow-hidden" wire:loading.class="opacity-60" wire:target="search, status, gotoPage, nextPage, previousPage">
        @forelse ($data as $item)
            <article wire:key="konten-{{ $item->id }}" class="flex flex-col gap-4 p-4 sm:flex-row sm:items-center md:p-5">
                <div class="flex min-w-0 flex-1 items-center gap-4">
                    <div class="h-20 w-28 shrink-0 overflow-hidden rounded-xl bg-stone-100">
                        @if ($item->image)
                            <img src="{{ storage_url($item->image) }}" alt="" class="h-full w-full object-cover" loading="lazy">
                        @else
                            <span class="flex h-full flex-col items-center justify-center gap-1 text-[11px] text-stone-400"><x-cms.icon name="image" /> Tanpa foto</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h3 class="line-clamp-2 font-semibold leading-snug text-stone-900">
                            <a href="{{ route('cms.' . $info['route'] . '.edit', ['id' => $item->id]) }}" class="hover:text-phb-green">{{ $item->title_id ?: $item->title_en }}</a>
                        </h3>
                        <div class="mt-2 flex flex-wrap items-center gap-1.5">
                            @if ($item->status === 'publish')
                                <span class="cms-badge bg-emerald-50 text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Terbit</span>
                            @else
                                <span class="cms-badge bg-amber-50 text-amber-800"><span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Draft</span>
                            @endif
                            @foreach (['id', 'en'] as $lang)
                                @if ($isi($item, $lang))
                                    <span class="cms-badge bg-stone-100 text-stone-600" title="Versi {{ $lang === 'id' ? 'Bahasa Indonesia' : 'English' }} sudah diisi"><x-cms.icon name="check" class="h-3.5 w-3.5 text-emerald-600" /> {{ strtoupper($lang) }}</span>
                                @else
                                    <span class="cms-badge bg-amber-50 text-amber-800" title="Versi {{ $lang === 'id' ? 'Bahasa Indonesia' : 'English' }} belum lengkap">{{ strtoupper($lang) }} belum</span>
                                @endif
                            @endforeach
                            <span class="ml-1 flex items-center gap-1 text-xs text-stone-500">
                                <x-cms.icon name="clock" class="h-3.5 w-3.5" />
                                {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d M Y') : '–' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-1.5 sm:shrink-0 sm:justify-end">
                    @if ($item->status === 'draft')
                        <button type="button" wire:click="toggleStatus({{ $item->id }})" class="cms-btn h-10 px-3 text-emerald-700">
                            <x-cms.icon name="send" class="h-4 w-4" /> Terbitkan
                        </button>
                    @endif
                    <a href="{{ route('cms.' . $info['route'] . '.edit', ['id' => $item->id]) }}" class="cms-btn h-10 px-3">
                        <x-cms.icon name="edit" class="h-4 w-4" /> Ubah
                    </a>
                    <a href="{{ konten_url($item) }}" target="_blank" rel="noopener" class="cms-btn-ghost" title="{{ $item->status === 'publish' ? 'Lihat di website' : 'Pratinjau (hanya terlihat oleh admin)' }}">
                        <x-cms.icon name="eye" class="h-4 w-4" /> {{ $item->status === 'publish' ? 'Lihat' : 'Pratinjau' }}
                    </a>
                    <button type="button" wire:click="delete({{ $item->id }})" wire:confirm="Hapus “{{ $item->title_id ?: $item->title_en }}”? Konten yang dihapus tidak bisa dikembalikan."
                            class="cms-btn-danger" aria-label="Hapus">
                        <x-cms.icon name="trash" class="h-4 w-4" /> <span class="md:hidden lg:inline">Hapus</span>
                    </button>
                </div>
            </article>
        @empty
            <div class="flex flex-col items-center px-6 py-16 text-center">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-phb-gold/15 text-phb-gold"><x-cms.icon :name="$info['icon']" class="h-7 w-7" /></span>
                @if ($search !== '' || $status !== '')
                    <p class="mt-4 font-semibold text-stone-800">Tidak ada yang cocok</p>
                    <p class="mt-1 text-sm text-stone-500">Coba kata kunci lain atau pilih “Semua”.</p>
                @else
                    <p class="mt-4 font-semibold text-stone-800">Belum ada {{ $info['nama'] }}</p>
                    <p class="mt-1 max-w-sm text-sm text-stone-500">Mulai dengan menambahkan yang pertama. Anda bisa menyimpannya sebagai draft dulu.</p>
                    <a href="{{ route('cms.' . $info['route'] . '.insert') }}" class="cms-btn-primary mt-5"><x-cms.icon name="plus" class="h-4 w-4" /> Tambah {{ $info['nama'] }}</a>
                @endif
            </div>
        @endforelse
    </div>

    @if ($data->hasPages())
        <div>{{ $data->links() }}</div>
    @endif
</div>
