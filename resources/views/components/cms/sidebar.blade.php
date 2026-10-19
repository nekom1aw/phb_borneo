@php
    $menu = [
        [
            'label' => null,
            'items' => [
                ['nama' => 'Ringkasan', 'icon' => 'home', 'url' => route('cms.index'), 'active' => request()->routeIs('cms.index')],
            ],
        ],
        [
            'label' => 'Halaman tetap',
            'items' => [
                ['nama' => 'Beranda & Footer', 'icon' => 'layout', 'url' => route('cms.beranda.index'), 'active' => request()->routeIs('cms.beranda.*')],
                ['nama' => 'Tentang Kami', 'icon' => 'info', 'url' => route('cms.about.index'), 'active' => request()->routeIs('cms.about.*')],
            ],
        ],
        [
            'label' => 'Konten',
            'items' => collect(config('phb.kategori'))->map(fn ($k) => [
                'nama' => $k['nama'],
                'icon' => $k['icon'],
                'url' => route('cms.' . $k['route'] . '.index'),
                'active' => request()->routeIs('cms.' . $k['route'] . '.*'),
            ])->values()->all(),
        ],
    ];

    if (session('account_role') === 'super_admin') {
        $menu[] = [
            'label' => 'Pengaturan',
            'items' => [
                ['nama' => 'Akun Pengguna', 'icon' => 'users', 'url' => route('cms.account.index'), 'active' => request()->routeIs('cms.account.*')],
            ],
        ];
    }

    $nama = session('account_nama', 'Admin');
    $inisial = collect(explode(' ', $nama))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
@endphp

{{-- latar gelap saat menu dibuka di layar kecil --}}
<div x-cloak x-show="sidebar" x-transition.opacity @click="sidebar = false" class="fixed inset-0 z-40 bg-stone-900/40 lg:hidden"></div>

<aside class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-phb-green text-white transition-transform duration-300 lg:translate-x-0"
       :class="sidebar && 'translate-x-0!'" aria-label="Menu panel admin">
    <div class="flex items-center gap-3 px-6 pb-6 pt-7">
        <span class="block w-9 text-phb-gold">@include('components.user.logo')</span>
        <div class="min-w-0 leading-tight">
            <p class="truncate text-[15px] font-semibold">{{ config('app.name') }}</p>
            <p class="text-xs text-white/60">Panel Admin</p>
        </div>
        <button type="button" class="ml-auto flex h-10 w-10 items-center justify-center rounded-xl text-white/70 hover:bg-white/10 lg:hidden" @click="sidebar = false" aria-label="Tutup menu">
            <x-cms.icon name="x" />
        </button>
    </div>

    <nav class="flex-1 space-y-6 overflow-y-auto px-4 pb-6">
        @foreach ($menu as $group)
            <div>
                @if ($group['label'])
                    <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.14em] text-white/45">{{ $group['label'] }}</p>
                @endif
                <ul class="space-y-1">
                    @foreach ($group['items'] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" @if ($item['active']) aria-current="page" @endif
                               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-[15px] transition {{ $item['active'] ? 'bg-white font-semibold text-phb-green shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                <x-cms.icon :name="$item['icon']" class="h-5 w-5 {{ $item['active'] ? 'text-phb-gold' : 'text-white/60' }}" />
                                {{ $item['nama'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    <div class="border-t border-white/10 p-4">
        <div class="flex items-center gap-3 rounded-xl bg-white/5 p-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-phb-gold text-sm font-bold">{{ $inisial }}</span>
            <div class="min-w-0 leading-tight">
                <p class="truncate text-sm font-semibold">{{ $nama }}</p>
                <p class="text-xs text-white/60">{{ session('account_role') === 'super_admin' ? 'Super admin' : 'Admin' }}</p>
            </div>
            <form method="POST" action="{{ route('cms.logout') }}" class="ml-auto">
                @csrf
                <button type="submit" class="flex h-10 w-10 items-center justify-center rounded-xl text-white/70 hover:bg-white/10 hover:text-white" title="Keluar" aria-label="Keluar">
                    <x-cms.icon name="logout" />
                </button>
            </form>
        </div>
    </div>
</aside>
