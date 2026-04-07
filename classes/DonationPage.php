<?php
require_once __DIR__ . '/Page.php';
require_once __DIR__ . '/Header.php';
require_once __DIR__ . '/Footer.php';

/**
 * Assembles and renders the Donation page.
 *
 * Screens: Campaign Overview → Campaign Tour → Loading →
 *          Donate Form → Thank You → Footer
 *
 * Campaign data lives here as the authoritative source.
 */
class DonationPage extends Page {
    private Header $header;
    private Footer $footer;
    private array  $campaigns;
    private string $pastorImg;

    public function __construct(string $base = '') {
        parent::__construct($base);

        $this->title       = 'Donate — Johnny Davis Global Missions';
        $this->description = 'Donate to Johnny Davis Global Missions — Feed Filipino Children, '
                           . 'support disaster relief, and bring hope to communities in need.';
        $this->cssFile     = 'style/for_donationpage.css';
        $this->jsFile      = 'js/for_donationpage.js';

        $this->header    = new Header($base, 'donation', 'donation');
        $this->footer    = new Footer($base, 'donation');
        $this->pastorImg = 'https://d14tal8bchn59o.cloudfront.net/RhGkp7h3Fm5bBymv78FLEpsQSnC3q7PFpecGpojrkak/w:2000/plain/https://02f0a56ef46d93f03c90-22ac5f107621879d5667e0d7ed595bdb.ssl.cf2.rackcdn.com/sites/104216/photos/23052432/JDM_Logo_6_original.jpg';
        $this->campaigns = $this->buildCampaigns();
    }

    /**
     * Authoritative campaign data.
     * To add or edit campaigns, update this method only.
     */
    private function buildCampaigns(): array {
        return [
            [
                'key'        => 'Feed Filipino Children',
                'icon'       => '🍽️',
                'label'      => 'Active Campaign',
                'status'     => 'active-campaign',
                'image'      => 'images/landingpage/feed_the_hungry.webp',
                'goal'       => '$45,000',
                'pct'        => 72,
                'raised'     => '$32,480',
                'total'      => '$45,000',
                'bar_style'  => '',
                'sub'        => '$7.99/month feeds one child — 100+ children currently enrolled',
                'meta'       => '$32,480 raised of $45,000 goal · 72% funded',
                'snippet'    => 'School lunch programs for 100+ children in Cebu City, building nutrition and future stability one meal at a time.',
                'story'      => '"Juan\'s first full meal in weeks."',
                'story_full' => 'Feed Filipino Children is providing hot meals and academic support to over 100 students in Cebu every month.',
                'goal_full'  => 'Goal: $45,000 to reach 100+ children for full school year',
                'tour_icon'  => '🍽️',
                'tour_num'   => 'Campaign 1',
            ],
            [
                'key'        => 'Cebu Earthquake Relief',
                'icon'       => '🏚️',
                'label'      => 'Urgent Relief',
                'status'     => 'urgent',
                'image'      => 'images/landingpage/pray_for_earthquake_victems.webp',
                'goal'       => '$30,000',
                'pct'        => 41,
                'raised'     => '$12,300',
                'total'      => '$30,000',
                'bar_style'  => 'background:linear-gradient(90deg,#c0392b,#e74c3c);',
                'sub'        => 'Emergency shelter, food packets, and rebuilding supplies for quake-affected families',
                'meta'       => '$12,300 raised of $30,000 goal · 41% funded',
                'snippet'    => 'Urgent tents, water, and medical care for communities displaced by the recent earthquake.',
                'story'      => '"Rebuilding hope after the tremors."',
                'story_full' => '',
                'goal_full'  => '',
                'tour_icon'  => '🏠',
                'tour_num'   => 'Campaign 2',
            ],
            [
                'key'        => 'Uganda Water Wells',
                'icon'       => '💧',
                'label'      => 'Water Access',
                'status'     => 'water',
                'image'      => 'images/landingpage/clean_drink_water.webp',
                'goal'       => '$22,000',
                'pct'        => 58,
                'raised'     => '$5,220',
                'total'      => '$9,000',
                'bar_style'  => 'background:linear-gradient(90deg,#1a4480,#2563a8);',
                'sub'        => '$4,500 funds one well · clean water for 200 people for 25 years',
                'meta'       => '$5,220 raised of $9,000 goal · 58% funded',
                'snippet'    => 'Permanent clean wells serving villages in Soroti — health and opportunity for 200 people each.',
                'story'      => '"No more boiled river water."',
                'story_full' => '',
                'goal_full'  => '',
                'tour_icon'  => '💧',
                'tour_num'   => 'Campaign 3',
            ],
        ];
    }

    protected function renderHead(): void {
        $base        = $this->base;
        $title       = $this->title;
        $description = $this->description;
        $cssFile     = $this->cssFile;
        include __DIR__ . '/../layouts/head-donation.php';
    }

    protected function renderBody(): void {
        $this->header->render();

        $campaigns = $this->campaigns;
        $pastorImg = $this->pastorImg;

        $this->component('campaign-overview', ['campaigns' => $campaigns]);
        $this->component('campaign-tour',     ['campaigns' => $campaigns]);
        $this->component('loading-screen');
        $this->component('donation-form',     ['campaigns' => $campaigns, 'pastor_img' => $pastorImg]);
        $this->component('thank-you',         ['pastor_img' => $pastorImg]);

        $this->footer->render();
    }
}
