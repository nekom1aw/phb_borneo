<?php

namespace App\Http\Controllers;

class CmsController extends Controller
{
    public function logout()
    {
        session()->forget(['account_id', 'account_role', 'account_nama']);
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }

    // login
    public function login()
    {
        return view('cms.pagelogin');
    }

    // dashboard
    public function index()
    {
        return view('cms.pageindex');
    }

    // beranda (hero, kutipan, footer)
    public function berandaIndex()
    {
        return view('cms.page-beranda');
    }

    // who we are
    public function aboutIndex()
    {
        return view('cms.page-about');
    }

    // account
    public function accountIndex()
    {
        return view('cms.page-account');
    }

    // our work (photo)
    public function ourworkIndex()
    {
        return view('cms.page-index-ourwork');
    }
    public function ourworkInsert()
    {
        return view('cms.page-insert-ourwork');
    }
    public function ourworkEdit($locale, $id)
    {
        return view('cms.page-edit-ourwork', compact('id'));
    }

    // resources
    public function resourcesIndex()
    {
        return view('cms.page-index-resources');
    }
    public function resourcesInsert()
    {
        return view('cms.page-insert-resources');
    }
    public function resourcesEdit($locale, $id)
    {
        return view('cms.page-edit-resources', compact('id'));
    }

    // event
    public function eventIndex()
    {
        return view('cms.page-index-event');
    }
    public function eventInsert()
    {
        return view('cms.page-insert-event');
    }
    public function eventEdit($locale, $id)
    {
        return view('cms.page-edit-event', compact('id'));
    }

    // blog
    public function blogIndex()
    {
        return view('cms.page-index-blog');
    }
    public function blogInsert()
    {
        return view('cms.page-insert-blog');
    }
    public function blogEdit($locale, $id)
    {
        return view('cms.page-edit-blog', compact('id'));
    }
}
