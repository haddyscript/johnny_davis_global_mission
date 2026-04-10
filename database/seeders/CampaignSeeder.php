<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $campaigns = [
            [
                'title'        => 'Feed Filipino Children',
                'icon'         => '🍽️',
                'label'        => 'Active Campaign',
                'status_class' => 'active-campaign',
                'goal_amount'  => '$45,000',
                'goal_pct'     => 72,
                'raised_amount'=> '$32,480',
                'bar_style'    => null,
                'subtitle'     => '$7.99/month feeds one child — 100+ children currently enrolled',
                'meta'         => '$32,480 raised of $45,000 goal · 72% funded',
                'snippet'      => 'School lunch programs for 100+ children in Cebu City, building nutrition and future stability one meal at a time.',
                'story'        => '"Juan\'s first full meal in weeks."',
                'story_full'   => 'Feed Filipino Children is providing hot meals and academic support to over 100 students in Cebu every month.',
                'goal_full'    => 'Goal: $45,000 to reach 100+ children for full school year',
                'sort_order'   => 1,
                'is_active'    => true,
            ],
            [
                'title'        => 'Cebu Earthquake Relief',
                'icon'         => '🏚️',
                'label'        => 'Urgent Relief',
                'status_class' => 'urgent',
                'goal_amount'  => '$30,000',
                'goal_pct'     => 41,
                'raised_amount'=> '$12,300',
                'bar_style'    => 'background:linear-gradient(90deg,#c0392b,#e74c3c);',
                'subtitle'     => 'Emergency shelter, food packets, and rebuilding supplies for quake-affected families',
                'meta'         => '$12,300 raised of $30,000 goal · 41% funded',
                'snippet'      => 'Urgent tents, water, and medical care for communities displaced by the recent earthquake.',
                'story'        => '"Rebuilding hope after the tremors."',
                'story_full'   => null,
                'goal_full'    => null,
                'sort_order'   => 2,
                'is_active'    => true,
            ],
            [
                'title'        => 'Uganda Water Wells',
                'icon'         => '💧',
                'label'        => 'Water Access',
                'status_class' => 'water',
                'goal_amount'  => '$22,000',
                'goal_pct'     => 58,
                'raised_amount'=> '$5,220',
                'bar_style'    => 'background:linear-gradient(90deg,#1a4480,#2563a8);',
                'subtitle'     => '$4,500 funds one well · clean water for 200 people for 25 years',
                'meta'         => '$5,220 raised of $9,000 goal · 58% funded',
                'snippet'      => 'Permanent clean wells serving villages in Soroti — health and opportunity for 200 people each.',
                'story'        => '"No more boiled river water."',
                'story_full'   => null,
                'goal_full'    => null,
                'sort_order'   => 3,
                'is_active'    => true,
            ],
        ];

        foreach ($campaigns as $data) {
            Campaign::firstOrCreate(
                ['title' => $data['title']],
                $data,
            );
        }
    }
}
