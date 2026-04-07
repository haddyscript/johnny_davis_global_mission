<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'title'       => 'Johnny Davis Global Missions — Feed Filipino Children',
            'description' => 'Johnny Davis Global Missions — Feed Filipino Children. Donate to fight hunger, support disaster relief, and bring hope to communities in the Philippines.',
        ]);
    }
}
