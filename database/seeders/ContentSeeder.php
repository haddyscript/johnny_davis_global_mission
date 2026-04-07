<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedHomePage();
            $this->seedContactPage();
            $this->seedNewsPage();
            $this->seedWhoWeArePage();
            $this->seedMinistryPage();
            $this->seedDonatePage();
        });
    }

    private function seedHomePage(): void
    {
        $home = $this->createPage(
            'home',
            'Home',
            'Johnny Davis Global Missions — Feed Filipino Children. Donate to fight hunger, support disaster relief, and bring hope to communities in the Philippines.',
            ['template' => 'home']
        );

        $this->createSection($home, 'hero', 'Hero', 'hero', [
            ['key' => 'eyebrow', 'type' => 'text', 'content' => 'Active Campaign 2026', 'sort_order' => 10],
            ['key' => 'headline', 'type' => 'text', 'content' => 'Feed the poorest Filipino children with food, water, and medical care.', 'sort_order' => 20],
            ['key' => 'subtitle', 'type' => 'text', 'content' => 'Your monthly gift keeps families fed and children in school across the Philippines.', 'sort_order' => 30],
            ['key' => 'description', 'type' => 'text', 'content' => 'Join our urgent campaign and provide meals, clean water, education support, and disaster relief to struggling communities.', 'sort_order' => 40],
            ['key' => 'primary_cta_label', 'type' => 'text', 'content' => 'Donate Now', 'url' => '/donate', 'sort_order' => 50],
            ['key' => 'secondary_cta_label', 'type' => 'text', 'content' => 'Watch Our Mission Video', 'url' => 'https://www.youtube.com/watch?v=s3DO45gx0KY', 'sort_order' => 60],
            ['key' => 'stats', 'type' => 'list', 'extra' => [
                ['value' => '5,000+', 'label' => 'Lives Touched'],
                ['value' => '3 Countries', 'label' => 'Supported'],
                ['value' => '25,000+', 'label' => 'Meals Delivered'],
                ['value' => '12K+', 'label' => 'Monthly Supporters'],
            ], 'sort_order' => 70],
        ]);

        $this->createSection($home, 'mission', 'Mission', 'content', [
            ['key' => 'headline', 'type' => 'text', 'content' => 'Bringing Hope to the Philippines', 'sort_order' => 10],
            ['key' => 'body', 'type' => 'text', 'content' => 'Johnny Davis Global Missions serves the most vulnerable families in the Philippines by providing food, medical care, education support, and disaster relief through long-term partnerships with local churches and community leaders.', 'sort_order' => 20],
            ['key' => 'pillars', 'type' => 'list', 'extra' => [
                ['title' => 'Food Security', 'description' => 'Delivering meals when families need them most.'],
                ['title' => 'Medical Missions', 'description' => 'Bringing care, medicine, and hope to remote communities.'],
                ['title' => 'Educational Support', 'description' => 'Helping children stay in school with supplies, tuition, and mentorship.'],
                ['title' => 'Clean Water', 'description' => 'Supporting wells and safe water access for families in rural areas.'],
                ['title' => 'Disaster Relief', 'description' => 'Responding immediately after storms and floods.'],
                ['title' => 'Community Outreach', 'description' => 'Empowering local leaders to build stronger neighborhoods.'],
            ], 'sort_order' => 30],
        ]);

        $this->createSection($home, 'donation_highlight', 'Donation Highlight', 'content', [
            ['key' => 'headline', 'type' => 'text', 'content' => 'Every contribution moves us closer to ending hunger for Filipino children.', 'sort_order' => 10],
            ['key' => 'body', 'type' => 'text', 'content' => 'Choose a monthly donation and we’ll provide a full meal plan, clean water, and hope to a child in need.', 'sort_order' => 20],
            ['key' => 'button_text', 'type' => 'text', 'content' => 'Donate & Change a Life', 'url' => '/donate', 'sort_order' => 30],
        ]);

        $this->createSection($home, 'urgency', 'Urgency', 'content', [
            ['key' => 'headline', 'type' => 'text', 'content' => 'Urgent Relief Needed', 'sort_order' => 10],
            ['key' => 'body', 'type' => 'text', 'content' => 'A typhoon left families without food, shelter, or clean water. Your support helps us mobilize immediate relief: emergency meals, hygiene kits, and medical care for children and families still recovering from the storm.', 'sort_order' => 20],
            ['key' => 'price', 'type' => 'text', 'content' => '$25 / month', 'sort_order' => 30],
        ]);

        $this->createSection($home, 'disaster', 'Disaster Relief', 'content', [
            ['key' => 'headline', 'type' => 'text', 'content' => 'Disaster Relief in the Philippines', 'sort_order' => 10],
            ['key' => 'body', 'type' => 'text', 'content' => 'Our teams are on the ground in Leyte, Cebu, and Mindanao delivering emergency meals, hygiene supplies, and safe drinking water to families still rebuilding.', 'sort_order' => 20],
            ['key' => 'highlights', 'type' => 'list', 'extra' => [
                ['title' => 'Food delivery', 'description' => 'Immediate, hot meals for families displaced by storms.'],
                ['title' => 'Water support', 'description' => 'Safe water and sanitation supplies for communities in crisis.'],
                ['title' => 'Medical care', 'description' => 'Basic medical treatment and referrals for children and adults.'],
            ], 'sort_order' => 30],
        ]);

        $this->createSection($home, 'help', 'How You Can Help', 'content', [
            ['key' => 'headline', 'type' => 'text', 'content' => 'How You Can Help', 'sort_order' => 10],
            ['key' => 'cards', 'type' => 'list', 'extra' => [
                ['title' => 'Make a Donation', 'description' => 'Your donation allows those in need to receive food, clean water, medical care and educational support.', 'cta_text' => 'Donate Now', 'cta_url' => '/donate'],
                ['title' => 'Become a Volunteer', 'description' => 'Help us participate in outreach programs and community events. Your presence transforms lives.', 'cta_text' => 'Get Involved', 'cta_url' => '/contact'],
                ['title' => 'Spread the Word', 'description' => 'Share our mission on social media and with your community to extend our reach.', 'cta_text' => 'Share Today', 'cta_url' => '/news'],
            ], 'sort_order' => 20],
        ]);

        $this->createSection($home, 'testimonials', 'Testimonials', 'content', [
            ['key' => 'headline', 'type' => 'text', 'content' => 'What People Are Saying About Us', 'sort_order' => 10],
            ['key' => 'quotes', 'type' => 'list', 'extra' => [
                ['quote' => 'This mission brought real change to our barangay. The children are healthier and attending school again.', 'author' => 'Pastor Esther A.', 'location' => 'Manila, Philippines'],
                ['quote' => 'The clean water project has changed our daily life. Our family is stronger and more hopeful.', 'author' => 'Aylen G.', 'location' => 'Leyte, Philippines'],
                ['quote' => 'I have never seen this much care go directly to the people who need it most.', 'author' => 'Yvonne M.', 'location' => 'Leyte, Philippines'],
            ], 'sort_order' => 20],
        ]);

        $this->createSection($home, 'donate_cta', 'Donate CTA', 'content', [
            ['key' => 'headline', 'type' => 'text', 'content' => 'Giving Back Feels Good', 'sort_order' => 10],
            ['key' => 'body', 'type' => 'text', 'content' => 'Show your support by making a donation today. Every gift — no matter the size — directly feeds a child, supports a family, and brings hope to a community in need.', 'sort_order' => 20],
            ['key' => 'primary_cta', 'type' => 'text', 'content' => 'Donate Now', 'url' => '/donate', 'sort_order' => 30],
            ['key' => 'secondary_cta', 'type' => 'text', 'content' => 'Give Monthly — $7.99', 'url' => '/donate', 'sort_order' => 40],
            ['key' => 'impact_note', 'type' => 'text', 'content' => '100% of your donation goes directly to those in need. Johnny Davis Global Missions is a 501(c)(3) nonprofit. Your gift may be tax-deductible.', 'sort_order' => 50],
        ]);
    }

    private function seedContactPage(): void
    {
        $contact = $this->createPage(
            'contact',
            'Contact',
            'Contact Johnny Davis Global Missions for questions about giving, volunteering, or church partnerships.',
            ['template' => 'contact']
        );

        $this->createSection($contact, 'hero', 'Hero', 'hero', [
            ['key' => 'eyebrow', 'type' => 'text', 'content' => 'Get Connected', 'sort_order' => 10],
            ['key' => 'headline', 'type' => 'text', 'content' => 'Get in Touch', 'sort_order' => 20],
            ['key' => 'subtitle', 'type' => 'text', 'content' => 'Questions about donating, volunteering, or church partnerships?', 'sort_order' => 30],
            ['key' => 'description', 'type' => 'text', 'content' => 'We respond to every message within 48 hours.', 'sort_order' => 40],
            ['key' => 'primary_cta', 'type' => 'text', 'content' => 'Send Us a Message', 'url' => '#contact-form-section', 'sort_order' => 50],
            ['key' => 'secondary_cta', 'type' => 'text', 'content' => 'Call (404) 426-2856', 'url' => 'tel:+14044262856', 'sort_order' => 60],
        ]);
    }

    private function seedNewsPage(): void
    {
        $news = $this->createPage(
            'news',
            'News',
            'Blog & News — Johnny Davis Global Missions. Monthly field updates, impact stories, and mission reports from the Philippines and Uganda.',
            ['template' => 'news']
        );

        $this->createSection($news, 'hero', 'Hero', 'hero', [
            ['key' => 'eyebrow', 'type' => 'text', 'content' => 'Stories from the Field', 'sort_order' => 10],
            ['key' => 'headline', 'type' => 'text', 'content' => 'Stories of Impact', 'sort_order' => 20],
            ['key' => 'subtitle', 'type' => 'text', 'content' => 'Updates From the Front Lines of Our Mission', 'sort_order' => 30],
            ['key' => 'description', 'type' => 'text', 'content' => 'Every meal served, every child helped, and every life changed has a story. Follow the journey of communities in the Philippines and Uganda as hope becomes reality.', 'sort_order' => 40],
            ['key' => 'primary_cta', 'type' => 'text', 'content' => 'Read Latest Stories', 'url' => '#posts-section', 'sort_order' => 50],
            ['key' => 'secondary_cta', 'type' => 'text', 'content' => 'Get Updates by Email', 'url' => '#newsletter', 'sort_order' => 60],
            ['key' => 'stats', 'type' => 'list', 'extra' => [
                ['value' => '6', 'label' => 'Reports this year'],
                ['value' => '2', 'label' => 'Countries covered'],
                ['value' => '2,400+', 'label' => 'Meals tracked monthly'],
            ], 'sort_order' => 70],
        ]);
    }

    private function seedWhoWeArePage(): void
    {
        $whoWeAre = $this->createPage(
            'who-we-are',
            'Who We Are',
            'Who We Are — Johnny Davis Global Missions. Meet the team behind the mission to transform lives and empower communities across the Philippines.',
            ['template' => 'standard']
        );

        $this->createSection($whoWeAre, 'hero', 'Hero', 'hero', [
            ['key' => 'eyebrow', 'type' => 'text', 'content' => 'Transforming Lives Since 2017', 'sort_order' => 10],
            ['key' => 'headline', 'type' => 'text', 'content' => 'Who We Are', 'sort_order' => 20],
            ['key' => 'subtitle', 'type' => 'text', 'content' => 'Together we can make a difference.', 'sort_order' => 30],
            ['key' => 'description', 'type' => 'text', 'content' => 'Bringing hope, food, care, and opportunity to communities in need across the Philippines — powered by faith and fueled by your generosity.', 'sort_order' => 40],
            ['key' => 'primary_cta', 'type' => 'text', 'content' => 'Donate Here', 'url' => '/donate', 'sort_order' => 50],
            ['key' => 'secondary_cta', 'type' => 'text', 'content' => 'Learn Our Story', 'url' => '#about', 'sort_order' => 60],
        ]);
    }

    private function seedMinistryPage(): void
    {
        $ministry = $this->createPage(
            'ministry',
            'Ministry',
            'Johnny Davis Ministries — Transforming Lives, Empowering Communities, Expanding the Kingdom of God. Explore the ministry, Daily Push videos, podcast, and upcoming events.',
            ['template' => 'standard']
        );

        $this->createSection($ministry, 'hero', 'Hero', 'hero', [
            ['key' => 'headline', 'type' => 'text', 'content' => 'Johnny Davis Ministries — Transforming Lives', 'sort_order' => 10],
            ['key' => 'subtitle', 'type' => 'text', 'content' => 'Explore our ministry updates, videos, podcast, and upcoming events.', 'sort_order' => 20],
            ['key' => 'description', 'type' => 'text', 'content' => 'Join us as we share stories of how the ministry brings hope, discipleship, and practical support to communities around the world.', 'sort_order' => 30],
            ['key' => 'primary_cta', 'type' => 'text', 'content' => 'Watch Daily Push', 'url' => '#daily-push', 'sort_order' => 40],
            ['key' => 'secondary_cta', 'type' => 'text', 'content' => 'View Upcoming Events', 'url' => '#upcoming-events', 'sort_order' => 50],
        ]);
    }

    private function seedDonatePage(): void
    {
        $donate = $this->createPage(
            'donate',
            'Donate',
            'Donate to Johnny Davis Global Missions — Feed Filipino Children, support disaster relief, and bring hope to communities in need.',
            ['template' => 'standard']
        );

        $this->createSection($donate, 'hero', 'Hero', 'hero', [
            ['key' => 'headline', 'type' => 'text', 'content' => 'Give Monthly to Feed Filipino Children', 'sort_order' => 10],
            ['key' => 'subtitle', 'type' => 'text', 'content' => 'Your gift helps deliver food, medicine, and education support to communities in need.', 'sort_order' => 20],
            ['key' => 'description', 'type' => 'text', 'content' => 'Choose a campaign to support urgent hunger relief, education sponsorship, or medical missions in the Philippines.', 'sort_order' => 30],
            ['key' => 'primary_cta', 'type' => 'text', 'content' => 'Donate Now', 'url' => '/donate', 'sort_order' => 40],
        ]);
    }

    private function createPage(string $slug, string $name, string $description, array $metadata = []): Page
    {
        return Page::updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'description' => $description,
                'metadata' => $metadata,
                'is_active' => true,
                'sort_order' => 0,
            ]
        );
    }

    private function createSection(Page $page, string $slug, string $name, string $type, array $blocks): void
    {
        $section = $page->sections()->updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'type' => $type,
                'settings' => [],
                'sort_order' => $page->sections()->count() * 10,
            ]
        );

        foreach ($blocks as $index => $blockData) {
            $section->contentBlocks()->updateOrCreate(
                ['key' => $blockData['key']],
                [
                    'type' => $blockData['type'] ?? 'text',
                    'content' => $blockData['content'] ?? null,
                    'url' => $blockData['url'] ?? null,
                    'extra' => $blockData['extra'] ?? null,
                    'sort_order' => $blockData['sort_order'] ?? ($index + 1) * 10,
                ]
            );
        }
    }
}
