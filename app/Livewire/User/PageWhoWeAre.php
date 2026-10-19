<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PageWhoWeAre extends Component
{
    public function render()
    {
        return view('livewire.user.page-who-we-are', [
            'setting' => DB::table('setting')->first(),
            'about' => DB::table('about')->first(),
        ]);
    }
}
