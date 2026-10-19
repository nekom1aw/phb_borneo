<?php

namespace App\Providers;

use App\Http\Middleware\cmslogin;
use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bahasa dan cek login ikut berlaku saat Livewire memproses aksi (wire:click, upload, dll)
        Livewire::addPersistentMiddleware([
            LanguageMiddleware::class,
            cmslogin::class,
        ]);

        // Footer butuh deskripsi dari tabel setting
        View::composer('components.user.footer', function ($view) {
            $view->with('setting', DB::table('setting')->first());
        });
    }
}
