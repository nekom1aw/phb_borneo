<?php

namespace App\Livewire\Cms\Concerns;

trait HarusLogin
{
    // Dicek di setiap request Livewire (render awal maupun aksi), bukan hanya saat halaman dibuka
    public function bootHarusLogin()
    {
        abort_unless(session()->has('account_id'), 403);
    }
}
