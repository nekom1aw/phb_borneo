<div class="flex flex-col gap-6">
    <x-cms.page-header title="Akun Pengguna" icon="users"
                       desc="Orang yang bisa masuk ke panel admin. Admin bisa mengisi semua konten; super admin juga bisa mengatur akun.">
        <button type="button" wire:click="create" class="cms-btn-primary">
            <x-cms.icon name="plus" class="h-4 w-4" /> Tambah akun
        </button>
    </x-cms.page-header>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($data as $account)
            @php
                $inisial = collect(explode(' ', $account->nama))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
                $saya = $account->id == session('account_id');
            @endphp
            <div wire:key="account-{{ $account->id }}" class="cms-card flex flex-col p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full {{ $account->status === 'Y' ? 'bg-phb-green text-white' : 'bg-stone-200 text-stone-500' }} font-bold">{{ $inisial }}</span>
                    <div class="min-w-0">
                        <p class="truncate font-semibold text-stone-900">{{ $account->nama }} @if ($saya)<span class="text-sm font-normal text-stone-400">(Anda)</span>@endif</p>
                        <p class="truncate text-sm text-stone-500">{{ $account->email }}</p>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-1.5">
                    <span class="cms-badge {{ $account->role === 'super_admin' ? 'bg-phb-gold/15 text-[#7a5a20]' : 'bg-stone-100 text-stone-600' }}">{{ $account->role === 'super_admin' ? 'Super admin' : 'Admin' }}</span>
                    @if ($account->status === 'Y')
                        <span class="cms-badge bg-emerald-50 text-emerald-700">Aktif</span>
                    @else
                        <span class="cms-badge bg-stone-100 text-stone-500">Nonaktif</span>
                    @endif
                </div>
                <div class="mt-5 flex gap-2 border-t border-stone-100 pt-4">
                    <button type="button" wire:click="edit({{ $account->id }})" class="cms-btn h-10 flex-1 px-3"><x-cms.icon name="edit" class="h-4 w-4" /> Ubah</button>
                    @unless ($saya)
                        <button type="button" wire:click="delete({{ $account->id }})" wire:confirm="Hapus akun {{ $account->nama }}? Orang ini tidak akan bisa masuk lagi." class="cms-btn-danger"><x-cms.icon name="trash" class="h-4 w-4" /> Hapus</button>
                    @endunless
                </div>
            </div>
        @endforeach
    </div>

    {{-- panel form (geser dari kanan) --}}
    @if ($showForm)
        <div class="fixed inset-0 z-[55] bg-stone-900/40" wire:click="cancel" aria-hidden="true"></div>
        <div class="fixed inset-y-0 right-0 z-[56] flex w-full max-w-md flex-col bg-white shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="judul-akun"
             x-data x-init="$nextTick(() => document.getElementById('nama').focus())" @keydown.escape.window="$wire.cancel()">
            <div class="flex items-center justify-between border-b border-stone-100 px-6 py-5">
                <h2 id="judul-akun" class="text-lg font-bold text-stone-900">{{ $accountId ? 'Ubah akun' : 'Akun baru' }}</h2>
                <button type="button" wire:click="cancel" class="cms-btn-ghost h-10 w-10 px-0" aria-label="Tutup"><x-cms.icon name="x" /></button>
            </div>

            <form wire:submit="save" class="flex flex-1 flex-col gap-5 overflow-y-auto px-6 py-6">
                <x-cms.field label="Nama" for="nama" required :error="$errors->first('nama')">
                    <input id="nama" type="text" wire:model="nama" class="cms-input" placeholder="Nama lengkap" @error('nama') aria-invalid="true" @enderror>
                </x-cms.field>
                <x-cms.field label="Email" for="email" required :error="$errors->first('email')" hint="Dipakai untuk masuk ke panel admin.">
                    <input id="email" type="email" wire:model="email" autocomplete="off" class="cms-input" placeholder="nama@contoh.org" @error('email') aria-invalid="true" @enderror>
                </x-cms.field>
                <x-cms.field label="Password" for="password" :required="! $accountId" :optional="(bool) $accountId" :error="$errors->first('password')"
                             :hint="$accountId ? 'Kosongkan bila tidak ingin mengganti. Minimal 8 karakter.' : 'Minimal 8 karakter. Berikan kepada pemilik akun.'">
                    <input id="password" type="password" wire:model="password" autocomplete="new-password" class="cms-input" @error('password') aria-invalid="true" @enderror>
                </x-cms.field>

                <fieldset class="flex flex-col gap-2">
                    <legend class="cms-label mb-2">Hak akses</legend>
                    @foreach (['admin' => ['Admin', 'Bisa mengisi dan mengubah semua konten website.'], 'super_admin' => ['Super admin', 'Seperti admin, ditambah bisa mengatur akun pengguna.']] as $value => [$label, $desc])
                        <label class="flex cursor-pointer gap-3 rounded-xl border p-3.5 transition {{ $role === $value ? 'border-phb-green bg-phb-green/5' : 'border-stone-200 hover:border-stone-300' }}">
                            <input type="radio" wire:model.live="role" value="{{ $value }}" class="mt-1 accent-[#123d2a]">
                            <span><span class="block text-sm font-semibold text-stone-900">{{ $label }}</span><span class="text-sm text-stone-500">{{ $desc }}</span></span>
                        </label>
                    @endforeach
                    @error('role') <p data-error class="text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                </fieldset>

                <fieldset class="flex flex-col gap-2">
                    <legend class="cms-label mb-2">Status</legend>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach (['Y' => 'Aktif (bisa masuk)', 'N' => 'Nonaktif'] as $value => $label)
                            <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border p-3 text-sm font-semibold transition {{ $status === $value ? 'border-phb-green bg-phb-green/5 text-stone-900' : 'border-stone-200 text-stone-600 hover:border-stone-300' }}">
                                <input type="radio" wire:model.live="status" value="{{ $value }}" class="accent-[#123d2a]"> {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="mt-auto flex gap-2 pt-4">
                    <button type="submit" class="cms-btn-primary h-12 flex-1" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">Simpan</span>
                        <span wire:loading wire:target="save">Menyimpan…</span>
                    </button>
                    <button type="button" wire:click="cancel" class="cms-btn h-12">Batal</button>
                </div>
            </form>
        </div>
    @endif
</div>
