<?php

namespace App\Livewire\Cms;

use App\Livewire\Cms\Concerns\HarusLogin;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    use HarusLogin;

    public function render()
    {
        $count = DB::table('konten')
            ->select('kategori', 'status', DB::raw('count(*) as total'))
            ->groupBy('kategori', 'status')
            ->get();

        $cards = [];
        foreach (config('phb.kategori') as $key => $kategori) {
            $cards[] = $kategori + [
                'publish' => (int) $count->where('kategori', $key)->where('status', 'publish')->sum('total'),
                'draft' => (int) $count->where('kategori', $key)->where('status', 'draft')->sum('total'),
            ];
        }

        // konten yang belum lengkap: English kosong, tanpa foto, atau masih draft
        $kurang = function ($q) {
            $q->whereNull('title_en')->orWhere('title_en', '')
                ->orWhereNull('content_en')->orWhere('content_en', '')
                ->orWhereNull('image')
                ->orWhere('status', 'draft');
        };

        $perluTotal = DB::table('konten')->where($kurang)->count();

        $perlu = DB::table('konten')
            ->where($kurang)
            ->orderByDesc('updated_at')
            ->limit(6)
            ->get()
            ->map(function ($row) {
                $row->alasan = array_values(array_filter([
                    (! $row->title_en || ! trim(strip_tags((string) $row->content_en))) ? 'English belum lengkap' : null,
                    ! $row->image ? 'Belum ada foto' : null,
                    $row->status === 'draft' ? 'Masih draft' : null,
                ]));

                return $row;
            });

        return view('livewire.cms.dashboard', compact('cards', 'perlu', 'perluTotal'));
    }
}
