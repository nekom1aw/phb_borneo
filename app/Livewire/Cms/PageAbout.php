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

class PageAbout extends Component
{
    use HarusLogin;
    use PesanValidasi;
    use WithFileUploads;

    public $logo_title_id, $logo_title_en, $logo_content_id, $logo_content_en;
    public $visi_title_id, $visi_title_en, $visi_content_id, $visi_content_en;
    public $misi_title_id, $misi_title_en, $misi_content_id, $misi_content_en;

    public $struktur_image;     // unggahan baru
    #[Locked]
    public $oldStruktur;        // path tersimpan

    // kolom teks yang disimpan apa adanya
    protected $fields = [
        'logo_title_id', 'logo_title_en', 'logo_content_id', 'logo_content_en',
        'visi_title_id', 'visi_title_en', 'visi_content_id', 'visi_content_en',
        'misi_title_id', 'misi_title_en', 'misi_content_id', 'misi_content_en',
    ];

    public function mount()
    {
        $row = DB::table('about')->first();

        if ($row) {
            foreach ($this->fields as $field) {
                $this->{$field} = $row->{$field};
            }
            $this->oldStruktur = $row->struktur_image;
        }
    }

    protected function rules()
    {
        $rules = ['struktur_image' => 'nullable|image|max:8192'];

        foreach ($this->fields as $field) {
            $rules[$field] = str_contains($field, '_title_') ? 'nullable|string|max:255' : 'nullable|string';
        }

        return $rules;
    }

    protected function validationAttributes()
    {
        return ['struktur_image' => 'Gambar struktur kepengurusan'];
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

        $data = ['updated_at' => now()];
        foreach ($this->fields as $field) {
            $data[$field] = str_contains($field, '_content_') ? bersihkan_html($this->{$field}) : $this->{$field};
        }

        if ($this->struktur_image) {
            $data['struktur_image'] = $this->struktur_image->store('about', 'public');
            if ($this->oldStruktur && ! str_starts_with($this->oldStruktur, 'seed/')) {
                Storage::disk('public')->delete($this->oldStruktur);
            }
            $this->oldStruktur = $data['struktur_image'];
        }

        if (DB::table('about')->exists()) {
            DB::table('about')->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('about')->insert($data);
        }

        $this->reset('struktur_image');

        $this->dispatch('cms-saved');
        $this->dispatch('toast', message: 'Perubahan tersimpan dan sudah tampil di website.');
    }

    public function render()
    {
        return view('livewire.cms.page-about');
    }
}
