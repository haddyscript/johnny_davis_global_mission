<?php

namespace App\Http\Controllers;

class WhoWeAreController extends Controller
{
    public function index()
    {
        return view('who-we-are', [
            'title'       => 'Who We Are — Johnny Davis Global Missions',
            'description' => 'Who We Are — Johnny Davis Global Missions. Meet the team behind the mission to transform lives and empower communities across the Philippines.',
        ]);
    }
}
