<?php

namespace App\Http\Controllers;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact', [
            'title'       => 'Contact — Johnny Davis Global Missions',
            'description' => 'Contact Johnny Davis Global Missions — Get in touch for donations, volunteering, church partnerships, and mission updates.',
        ]);
    }
}
