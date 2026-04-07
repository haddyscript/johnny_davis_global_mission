<?php

namespace App\Http\Controllers;

use App\Services\CampaignService;

class DonationController extends Controller
{
    public function index()
    {
        return view('donation', [
            'title' => 'Donate — Johnny Davis Global Missions',
            'description' => 'Donate to Johnny Davis Global Missions — Feed Filipino Children, support disaster relief, and bring hope to communities in need.',
            'campaigns' => CampaignService::getCampaigns(),
        ]);
    }
}
