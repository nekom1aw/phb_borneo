<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PageKontenDetail extends Component
{
    // resources | event | photo | blog (tidak bisa diubah dari browser)
    #[Locked]
    public $kategori;

    #[Locked]
    public $kontenId;

    public function mount($kategori, $id)
    {
        // draft hanya bisa dilihat admin yang sedang login (pratinjau)
        $exists = DB::table('konten')
            ->where('id', $id)
            ->where('kategori', $kategori)
            ->when(! session()->has('account_id'), fn ($q) => $q->where('status', 'publish'))
            ->exists();

        abort_unless($exists, 404);

        $this->kategori = $kategori;
        $this->kontenId = $id;
    }

    public function render()
    {
        return view('livewire.user.page-konten-detail', [
            'setting' => DB::table('setting')->first(),
            'heading' => config('phb.kategori.' . $this->kategori . '.heading'),
            'item' => DB::table('konten')
                ->where('id', $this->kontenId)
                ->where('kategori', $this->kategori)
                ->when(! session()->has('account_id'), fn ($q) => $q->where('status', 'publish'))
                ->firstOrFail(),
        ]);
    }
}
