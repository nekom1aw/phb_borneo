<?php

namespace App\Livewire\Cms;

use App\Livewire\Cms\Concerns\HarusLogin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class KontenIndex extends Component
{
    use HarusLogin;
    use WithPagination;

    // resources | event | photo | blog (tidak bisa diubah dari browser)
    #[Locked]
    public $kategori;

    #[Url(except: '')]
    public $search = '';

    #[Url(except: '')]
    public $status = '';

    public function mount($kategori)
    {
        abort_unless(array_key_exists($kategori, config('phb.kategori')), 404);

        $this->kategori = $kategori;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $row = DB::table('konten')
            ->where('id', $id)
            ->where('kategori', $this->kategori)
            ->first();

        if (! $row) return;

        DB::table('konten')
            ->where('id', $id)
            ->update([
                'status' => $row->status === 'publish' ? 'draft' : 'publish',
                'updated_at' => now(),
            ]);

        $this->dispatch('toast', message: $row->status === 'publish' ? 'Konten disembunyikan dari website (draft).' : 'Konten sudah terbit di website.');
    }

    public function delete($id)
    {
        $row = DB::table('konten')
            ->where('id', $id)
            ->where('kategori', $this->kategori)
            ->first();

        if (! $row) return;

        // hapus file yang diunggah (file contoh di folder seed dibiarkan)
        foreach ([$row->image, $row->file] as $path) {
            if ($path && ! str_starts_with($path, 'seed/')) {
                Storage::disk('public')->delete($path);
            }
        }

        DB::table('konten')
            ->where('id', $id)
            ->delete();

        $this->dispatch('toast', message: 'Konten berhasil dihapus.');
    }

    public function render()
    {
        $search = trim($this->search);

        $data = DB::table('konten')
            ->where('kategori', $this->kategori)
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('title_id', 'ilike', '%' . $search . '%')
                        ->orWhere('title_en', 'ilike', '%' . $search . '%');
                });
            })
            ->when(in_array($this->status, ['draft', 'publish']), fn ($q) => $q->where('status', $this->status))
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10);

        $jumlah = DB::table('konten')
            ->where('kategori', $this->kategori)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('livewire.cms.konten-index', [
            'data' => $data,
            'info' => config('phb.kategori.' . $this->kategori),
            'jumlah' => [
                '' => (int) $jumlah->sum(),
                'publish' => (int) ($jumlah['publish'] ?? 0),
                'draft' => (int) ($jumlah['draft'] ?? 0),
            ],
        ]);
    }
}
