{{-- Ajakan menyalin teks Bahasa Indonesia ke tab English --}}
<div class="flex flex-col gap-3 rounded-2xl bg-phb-gold/10 p-4 sm:flex-row sm:items-center">
    <x-cms.icon name="bulb" class="hidden h-6 w-6 text-phb-gold sm:block" />
    <p class="flex-1 text-sm text-stone-700">Belum sempat menerjemahkan? Salin teks Bahasa Indonesia ke sini, lalu ubah ke English. Bila English kosong, pengunjung akan melihat versi Bahasa Indonesia.</p>
    <button type="button" class="cms-btn h-10 shrink-0" @click="copyFromId('Isi English yang sudah ada akan diganti dengan teks Bahasa Indonesia. Lanjutkan?')">
        <x-cms.icon name="copy" class="h-4 w-4" /> Salin dari Bahasa Indonesia
    </button>
</div>
