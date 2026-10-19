<?php

namespace App\Livewire\Cms;

use App\Livewire\Cms\Concerns\HarusLogin;
use App\Livewire\Cms\Concerns\PesanValidasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class PageBeranda extends Component
{
    use HarusLogin;
    use PesanValidasi;
    use WithFileUploads;

    public $quote_id;
    public $quote_en;

    public $footer_id;
    public $footer_en;

    public $hero_home;      // unggahan baru
    public $hero_page;
    #[Locked]
    public $oldHeroHome;    // path tersimpan

    #[Locked]
    public $oldHeroPage;

    public function mount()
    {
        $row = DB::table('setting')->first();

        if ($row) {
            $this->quote_id = $row->quote_id;
            $this->quote_en = $row->quote_en;
            $this->footer_id = $row->footer_id;
            $this->footer_en = $row->footer_en;
            $this->oldHeroHome = $row->hero_home;
            $this->oldHeroPage = $row->hero_page;
        }
    }

    protected function rules()
    {
        return [
            'quote_id' => 'required|string|max:255',
            'quote_en' => 'required|string|max:255',
            'footer_id' => 'nullable|string|max:1000',
            'footer_en' => 'nullable|string|max:1000',
            'hero_home' => 'nullable|image|max:8192',
            'hero_page' => 'nullable|image|max:8192',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'quote_id' => 'Kutipan (ID)',
            'quote_en' => 'Kutipan (EN)',
            'footer_id' => 'Deskripsi footer (ID)',
            'footer_en' => 'Deskripsi footer (EN)',
            'hero_home' => 'Foto hero Beranda',
            'hero_page' => 'Foto hero halaman lain',
        ];
    }

    public function save()
    {
        try {
            $this->validate();
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $this->dispatch('form-invalid', fields: array_keys($e->errors()));
            $this->dispatch('toast', message: 'Ada isian yang perlu diperbaiki.', type: 'error');
            return;
        }

        $data = [
            'quote_id' => $this->quote_id,
            'quote_en' => $this->quote_en,
            'footer_id' => $this->footer_id,
            'footer_en' => $this->footer_en,
            'updated_at' => now(),
        ];

        if ($this->hero_home) {
            $data['hero_home'] = $this->hero_home->store('hero', 'public');
            $this->deleteOld($this->oldHeroHome);
            $this->oldHeroHome = $data['hero_home'];
        }

        if ($this->hero_page) {
            $data['hero_page'] = $this->hero_page->store('hero', 'public');
            $this->deleteOld($this->oldHeroPage);
            $this->oldHeroPage = $data['hero_page'];
        }

        if (DB::table('setting')->exists()) {
            DB::table('setting')->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('setting')->insert($data);
        }

        $this->reset('hero_home', 'hero_page');

        $this->dispatch('cms-saved');
        $this->dispatch('toast', message: 'Perubahan tersimpan dan sudah tampil di website.');
    }

    protected function deleteOld($path)
    {
        if ($path && ! str_starts_with($path, 'seed/')) {
            Storage::disk('public')->delete($path);
        }
    }

    public function render()
    {
        return view('livewire.cms.page-beranda');
    }
}
