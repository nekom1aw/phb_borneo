{{-- Tab bahasa dengan tanda kelengkapan. Dipakai di dalam x-data="cmsForm([...])" --}}
<div class="grid grid-cols-2 gap-1.5 rounded-2xl bg-stone-100 p-1.5" role="tablist" aria-label="Bahasa isi">
    @foreach (['id' => 'Bahasa Indonesia', 'en' => 'English'] as $lang => $nama)
        <button type="button" role="tab" :aria-selected="tab === '{{ $lang }}'" @click="tab = '{{ $lang }}'"
                class="flex items-center justify-center gap-2 rounded-xl px-3 py-2.5 text-sm font-semibold transition"
                :class="tab === '{{ $lang }}' ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-500 hover:text-stone-800'">
            <span class="rounded-md bg-stone-900/5 px-1.5 py-0.5 text-[11px] font-bold tracking-wide text-stone-600">{{ strtoupper($lang) }}</span>
            <span class="hidden sm:inline">{{ $nama }}</span>
            <span class="h-2 w-2 rounded-full"
                  :class="{ 'bg-emerald-500': langState('{{ $lang }}') === 'done', 'bg-amber-400': langState('{{ $lang }}') === 'partial', 'bg-stone-300': langState('{{ $lang }}') === 'empty' }"
                  :title="{ done: 'Sudah lengkap', partial: 'Belum lengkap', empty: 'Belum diisi' }[langState('{{ $lang }}')]"></span>
        </button>
    @endforeach
</div>
