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
            'campaigns' => CampaignService::getCampaigns(),            'pastorImg'   => 'https://d14tal8bchn59o.cloudfront.net/RhGkp7h3Fm5bBymv78FLEpsQSnC3q7PFpecGpojrkak/w:2000/plain/https://02f0a56ef46d93f03c90-22ac5f107621879d5667e0d7ed595bdb.ssl.cf2.rackcdn.com/sites/104216/photos/23052432/JDM_Logo_6_original.jpg',        ]);
    }
}
