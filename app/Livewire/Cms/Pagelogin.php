<?php

namespace App\Livewire\Cms;

use App\Livewire\Cms\Concerns\PesanValidasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class Pagelogin extends Component
{
    use PesanValidasi;

    public string $email = '';
    public string $password = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function mount()
    {
        if (session()->has('account_id')) {
            return redirect('/id/cms');
        }
    }

    public function login()
    {
        $this->validate();

        $throttleKey = Str::lower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('email', "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.");
            return;
        }

        $user = DB::table('accounts')
            ->where('email', Str::lower($this->email))
            ->first();

        if (! $user || ! Hash::check($this->password, $user->password)) {
            RateLimiter::hit($throttleKey, 60);
            $this->addError('email', 'Email atau password salah.');
            return;
        }

        if ($user->status !== 'Y') {
            $this->addError('email', 'Akun ini dinonaktifkan.');
            return;
        }

        RateLimiter::clear($throttleKey);

        session()->regenerate();
        session()->put('account_id', $user->id);
        session()->put('account_role', $user->role);
        session()->put('account_nama', $user->nama);

        return redirect('/id/cms');
    }

    public function render()
    {
        return view('livewire.cms.pagelogin', [
            'hero' => DB::table('setting')->value('hero_home'),
        ]);
    }
}
