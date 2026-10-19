<?php

use App\Http\Controllers\CmsController;
use App\Http\Controllers\TinyUploadController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\cmslogin;
use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Support\Facades\Route;

Route::pattern('locale', 'en|id');
Route::pattern('id', '[0-9]+');

Route::get('/', function () {
    return redirect('/' . app()->getLocale());
});

// login CMS
Route::get('/cms/login', [CmsController::class, 'login'])
    ->name('login');

// panel admin memakai bahasa Indonesia
Route::get('/cms', function () {
    return redirect('/id/cms');
});

// user routes
Route::middleware(LanguageMiddleware::class)
    ->prefix('{locale}')
    ->group(function () {

        // home
        Route::get('/', [UserController::class, 'index'])
            ->name('index');

        // who we are
        Route::get('/who-we-are', [UserController::class, 'whoWeAre'])
            ->name('whoweare');

        // our work (photo)
        Route::get('/our-work', [UserController::class, 'ourworkIndex'])
            ->name('ourwork.index');
        Route::get('/our-work/{id}/{slug?}', [UserController::class, 'ourworkDetail'])
            ->name('ourwork.detail');

        // resources
        Route::get('/resources', [UserController::class, 'resourcesIndex'])
            ->name('resources.index');
        Route::get('/resources/{id}/{slug?}', [UserController::class, 'resourcesDetail'])
            ->name('resources.detail');

        // event
        Route::get('/event', [UserController::class, 'eventIndex'])
            ->name('event.index');
        Route::get('/event/{id}/{slug?}', [UserController::class, 'eventDetail'])
            ->name('event.detail');

        // blog
        Route::get('/blog', [UserController::class, 'blogIndex'])
            ->name('blog.index');
        Route::get('/blog/{id}/{slug?}', [UserController::class, 'blogDetail'])
            ->name('blog.detail');
    });

// CMS routes
Route::prefix('{locale}/cms')
    ->name('cms.')
    ->middleware([
        LanguageMiddleware::class,
        cmslogin::class . ':admin,super_admin',
    ])
    ->group(function () {

        Route::post('/logout', [CmsController::class, 'logout'])->name('logout');

        // dashboard
        Route::get('/', [CmsController::class, 'index'])->name('index');

        // beranda
        Route::get('/beranda', [CmsController::class, 'berandaIndex'])->name('beranda.index');

        // who we are
        Route::get('/who-we-are', [CmsController::class, 'aboutIndex'])->name('about.index');

        // our work (photo)
        Route::get('/our-work', [CmsController::class, 'ourworkIndex'])->name('ourwork.index');
        Route::get('/our-work/insert', [CmsController::class, 'ourworkInsert'])->name('ourwork.insert');
        Route::get('/our-work/edit/{id}', [CmsController::class, 'ourworkEdit'])->name('ourwork.edit');

        // resources
        Route::get('/resources', [CmsController::class, 'resourcesIndex'])->name('resources.index');
        Route::get('/resources/insert', [CmsController::class, 'resourcesInsert'])->name('resources.insert');
        Route::get('/resources/edit/{id}', [CmsController::class, 'resourcesEdit'])->name('resources.edit');

        // event
        Route::get('/event', [CmsController::class, 'eventIndex'])->name('event.index');
        Route::get('/event/insert', [CmsController::class, 'eventInsert'])->name('event.insert');
        Route::get('/event/edit/{id}', [CmsController::class, 'eventEdit'])->name('event.edit');

        // blog
        Route::get('/blog', [CmsController::class, 'blogIndex'])->name('blog.index');
        Route::get('/blog/insert', [CmsController::class, 'blogInsert'])->name('blog.insert');
        Route::get('/blog/edit/{id}', [CmsController::class, 'blogEdit'])->name('blog.edit');

        // upload gambar dari TinyMCE
        Route::post('/tinymce/upload', [TinyUploadController::class, 'store'])->name('tinymce.upload');
    });

// akun hanya untuk super admin
Route::prefix('{locale}/cms')
    ->name('cms.')
    ->middleware([
        LanguageMiddleware::class,
        cmslogin::class . ':super_admin',
    ])
    ->group(function () {
        Route::get('/account', [CmsController::class, 'accountIndex'])->name('account.index');
    });

Route::fallback(function () {
    abort(404);
});
