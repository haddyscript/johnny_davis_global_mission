<?php

namespace App\Http\Controllers;

class MinistryController extends Controller
{
    public function index()
    {
        return view('ministry', [
            'title'       => 'Johnny Davis Ministries — Transforming Lives',
            'description' => 'Johnny Davis Ministries — Transforming Lives, Empowering Communities, Expanding the Kingdom of God. Explore the ministry, Daily Push videos, podcast, and upcoming events.',
        ]);
    }
}
