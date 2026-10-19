<div class="grid min-h-screen lg:grid-cols-[1.1fr_1fr]">
    {{-- foto & tagline --}}
    <div class="relative hidden overflow-hidden bg-phb-green lg:block">
        <img src="{{ storage_url($hero) ?: asset('storage/seed/hero-home.jpg') }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-70">
        <div class="absolute inset-0 bg-gradient-to-t from-phb-green via-phb-green/40 to-phb-green/10"></div>
        <div class="relative flex h-full flex-col justify-between p-12 text-white">
            <div class="flex items-center gap-3">
                <span class="block w-10 text-phb-gold">@include('components.user.logo')</span>
                <span class="text-lg font-semibold">{{ config('app.name') }}</span>
            </div>
            <div>
                <p class="max-w-md text-4xl font-semibold leading-tight">Kami berpikir, kami masuk belantara.</p>
                <p class="mt-4 max-w-md text-white/75">Panel untuk memperbarui isi website: berita, publikasi, kegiatan, dan foto.</p>
            </div>
        </div>
    </div>

    {{-- form --}}
    <div class="flex items-center justify-center px-5 py-12">
        <div class="w-full max-w-md">
            <div class="flex items-center gap-3 lg:hidden">
                <span class="block w-10 text-phb-green">@include('components.user.logo')</span>
                <span class="text-lg font-semibold text-phb-green">{{ config('app.name') }}</span>
            </div>

            <h1 class="mt-8 text-3xl font-bold tracking-tight text-stone-900 lg:mt-0">Selamat datang</h1>
            <p class="mt-2 text-[15px] text-stone-500">Masuk dengan email dan password yang diberikan pengelola website.</p>

            <form wire:submit="login" class="mt-8 flex flex-col gap-5" x-data="{ show: false }">
                <x-cms.field label="Email" for="email" :error="$errors->first('email')">
                    <div class="relative">
                        <x-cms.icon name="mail" class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400" />
                        <input id="email" type="email" wire:model="email" autocomplete="username" autofocus placeholder="nama@contoh.org"
                               class="cms-input pl-11" @error('email') aria-invalid="true" @enderror>
                    </div>
                </x-cms.field>

                <x-cms.field label="Password" for="password" :error="$errors->first('password')">
                    <div class="relative">
                        <x-cms.icon name="lock" class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400" />
                        <input id="password" :type="show ? 'text' : 'password'" wire:model="password" autocomplete="current-password"
                               class="cms-input pl-11 pr-12" @error('password') aria-invalid="true" @enderror>
                        <button type="button" @click="show = !show" class="absolute right-1.5 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-stone-400 hover:bg-stone-100 hover:text-stone-700"
                                :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'">
                            <x-cms.icon name="eye" class="h-5 w-5" />
                        </button>
                    </div>
                </x-cms.field>

                <button type="submit" class="cms-btn-primary mt-2 h-12 w-full text-[15px]" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="login">Masuk</span>
                    <span wire:loading wire:target="login">Memeriksa…</span>
                </button>

                <p class="text-center text-sm text-stone-500">Lupa password? Hubungi super admin website.</p>
            </form>

            <a href="{{ url('/id') }}" class="mt-10 inline-flex items-center gap-1.5 text-sm font-semibold text-stone-500 hover:text-phb-green">
                <x-cms.icon name="arrow-left" class="h-4 w-4" /> Kembali ke website
            </a>
        </div>
    </div>
</div>
