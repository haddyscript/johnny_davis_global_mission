<?php

namespace Database\Seeders;

use App\Models\NavItem;
use Illuminate\Database\Seeder;

class NavItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['label' => 'Home',          'url' => '/',             'nav_class' => null,              'sort_order' => 10],
            ['label' => 'Mission',       'url' => '/#mission',     'nav_class' => null,              'sort_order' => 20],
            ['label' => 'Blog & News',   'url' => '/news',         'nav_class' => null,              'sort_order' => 30],
            ['label' => 'Who We Are',    'url' => '/who-we-are',   'nav_class' => null,              'sort_order' => 40],
            ['label' => 'Ministry',      'url' => '/ministry',     'nav_class' => null,              'sort_order' => 50],
            ['label' => '♥ Donate',      'url' => '/donate',       'nav_class' => 'btn-nav-donate',  'sort_order' => 60],
            ['label' => 'Contact',       'url' => '/contact',      'nav_class' => null,              'sort_order' => 70],
        ];

        foreach ($items as $item) {
            NavItem::updateOrCreate(
                ['label' => $item['label']],
                array_merge($item, ['is_active' => true, 'is_external' => false])
            );
        }
    }
}
