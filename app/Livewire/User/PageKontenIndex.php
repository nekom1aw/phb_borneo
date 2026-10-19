<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class PageKontenIndex extends Component
{
    use WithPagination;

    // resources | event | photo | blog (tidak bisa diubah dari browser)
    #[Locked]
    public $kategori;

    public function mount($kategori)
    {
        abort_unless(array_key_exists($kategori, config('phb.kategori')), 404);

        $this->kategori = $kategori;
    }

    public function render()
    {
        $data = DB::table('konten')
            ->where('kategori', $this->kategori)
            ->where('status', 'publish')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.user.page-konten-index', [
            'setting' => DB::table('setting')->first(),
            'heading' => config('phb.kategori.' . $this->kategori . '.heading'),
            'data' => $data,
        ]);
    }
}
