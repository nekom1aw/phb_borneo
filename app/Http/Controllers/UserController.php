<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    // home
    public function index()
    {
        return view('user.index');
    }

    // who we are
    public function whoWeAre()
    {
        return view('user.page-who-we-are');
    }

    // our work (photo)
    public function ourworkIndex()
    {
        return view('user.page-ourwork-index');
    }
    public function ourworkDetail($locale, $id, $slug = null)
    {
        return view('user.page-ourwork-detail', compact('id', 'slug'));
    }

    // resources
    public function resourcesIndex()
    {
        return view('user.page-resources-index');
    }
    public function resourcesDetail($locale, $id, $slug = null)
    {
        return view('user.page-resources-detail', compact('id', 'slug'));
    }

    // event
    public function eventIndex()
    {
        return view('user.page-event-index');
    }
    public function eventDetail($locale, $id, $slug = null)
    {
        return view('user.page-event-detail', compact('id', 'slug'));
    }

    // blog
    public function blogIndex()
    {
        return view('user.page-blog-index');
    }
    public function blogDetail($locale, $id, $slug = null)
    {
        return view('user.page-blog-detail', compact('id', 'slug'));
    }
}
