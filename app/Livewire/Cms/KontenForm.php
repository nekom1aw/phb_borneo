<?php

namespace App\Livewire\Cms;

use App\Livewire\Cms\Concerns\HarusLogin;
use App\Livewire\Cms\Concerns\PesanValidasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class KontenForm extends Component
{
    use HarusLogin;
    use PesanValidasi;
    use WithFileUploads;

    // resources | event | photo | blog (id & path file dikunci: tidak bisa diubah dari browser)
    #[Locked]
    public $kategori;

    #[Locked]
    public $kontenId;

    public $status = 'draft';
    public $tanggal;

    public $title_id;
    public $title_en;

    public $deskripsi_id;
    public $deskripsi_en;

    public $content_id;
    public $content_en;

    public $image;      // unggahan baru
    #[Locked]
    public $oldImage;   // path yang tersimpan

    public $file;       // PDF baru (resources)
    #[Locked]
    public $oldFile;

    #[Locked]
    public $updatedAt;  // waktu terakhir disimpan

    public function mount($kategori, $id = null)
    {
        abort_unless(array_key_exists($kategori, config('phb.kategori')), 404);

        $this->kategori = $kategori;
        $this->tanggal = now()->toDateString();

        if ($id) {
            $row = DB::table('konten')
                ->where('id', $id)
                ->where('kategori', $kategori)
                ->first();

            abort_unless($row, 404);

            $this->kontenId = $row->id;
            $this->status = $row->status;
            $this->tanggal = $row->tanggal;
            $this->title_id = $row->title_id;
            $this->title_en = $row->title_en;
            $this->deskripsi_id = $row->deskripsi_id;
            $this->deskripsi_en = $row->deskripsi_en;
            $this->content_id = $row->content_id;
            $this->content_en = $row->content_en;
            $this->oldImage = $row->image;
            $this->oldFile = $row->file;
            $this->updatedAt = $row->updated_at;
        }
    }

    protected function rules()
    {
        return [
            'title_id' => 'required_without:title_en|nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'deskripsi_id' => 'nullable|string|max:1000',
            'deskripsi_en' => 'nullable|string|max:1000',
            'content_id' => 'nullable|string',
            'content_en' => 'nullable|string',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,publish',
            'image' => 'nullable|image|max:4096',
            'file' => 'nullable|file|mimes:pdf|max:20480',
        ];
    }

    public function removeFile()
    {
        if ($this->oldFile && ! str_starts_with($this->oldFile, 'seed/')) {
            Storage::disk('public')->delete($this->oldFile);
        }

        if ($this->kontenId) {
            DB::table('konten')->where('id', $this->kontenId)->update(['file' => null, 'updated_at' => now()]);
        }

        $this->oldFile = null;
        $this->file = null;
    }

    // $status: 'publish' (Terbitkan) atau 'draft' (Simpan draft); kosong = status tetap
    public function save($status = null)
    {
        if (in_array($status, ['draft', 'publish'])) {
            $this->status = $status;
        }

        try {
            $this->validate();
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $this->dispatch('form-invalid', fields: array_keys($e->errors()));
            $this->dispatch('toast', message: 'Ada isian yang perlu diperbaiki.', type: 'error');
            return;
        }

        $data = [
            'kategori' => $this->kategori,
            'slug' => Str::slug($this->title_en ?: $this->title_id),
            'tanggal' => $this->tanggal,
            'title_id' => $this->title_id,
            'title_en' => $this->title_en,
            'deskripsi_id' => $this->deskripsi_id,
            'deskripsi_en' => $this->deskripsi_en,
            'content_id' => bersihkan_html($this->content_id),
            'content_en' => bersihkan_html($this->content_en),
            'status' => $this->status,
            'updated_at' => now(),
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('konten', 'public');
            $this->deleteOld($this->oldImage);
        }

        if ($this->file) {
            $data['file'] = $this->file->storeAs('files', Str::slug(pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . Str::random(6) . '.pdf', 'public');
            $this->deleteOld($this->oldFile);
        }

        if ($this->kontenId) {
            DB::table('konten')
                ->where('id', $this->kontenId)
                ->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('konten')->insert($data);
        }

        session()->flash('success', $this->status === 'publish'
            ? 'Tersimpan dan sudah tampil di website.'
            : 'Tersimpan sebagai draft (belum tampil di website).');

        return redirect()->route('cms.' . config('phb.kategori.' . $this->kategori . '.route') . '.index', [
            'locale' => app()->getLocale(),
        ]);
    }

    protected function deleteOld($path)
    {
        if ($path && ! str_starts_with($path, 'seed/')) {
            Storage::disk('public')->delete($path);
        }
    }

    public function render()
    {
        return view('livewire.cms.konten-form', [
            'info' => config('phb.kategori.' . $this->kategori),
        ]);
    }
}
