<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    protected function latest($kategori, $limit)
    {
        return DB::table('konten')
            ->where('kategori', $kategori)
            ->where('status', 'publish')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function render()
    {
        return view('livewire.user.index', [
            'setting' => DB::table('setting')->first(),
            'resources' => $this->latest('resources', 2),
            'events' => $this->latest('event', 1),
            'photos' => $this->latest('photo', 2),
        ]);
    }
}
