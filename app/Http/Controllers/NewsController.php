<?php

namespace App\Http\Controllers;

use App\Helpers\CmsPageData;
use App\Models\Page;

class NewsController extends Controller
{
    public function index()
    {
        $page = Page::with(['sections' => fn($q) => $q->orderBy('sort_order')
            ->with(['contentBlocks' => fn($q) => $q->orderBy('sort_order')])])
            ->where('slug', 'news')
            ->where('is_active', true)
            ->first();

        $cms = new CmsPageData($page);

        return view('news', [
            'title'       => $cms->text('meta', 'title', 'Blog & News — Johnny Davis Global Missions'),
            'description' => $cms->text('meta', 'description', 'Blog & News — Johnny Davis Global Missions. Monthly field updates, impact stories, and mission reports from the Philippines and Uganda.'),
            'cms'         => $cms,
            'posts'       => $this->getPosts(),
        ]);
    }

    private function getPosts(): array
    {
        return [
            [
                'featured'   => true,
                'categories' => 'field-reports',
                'country'    => 'Philippines',
                'flag'       => '🇵🇭',
                'image'      => 'images/landingpage/feed_the_hungry.webp',
                'img_alt'    => 'Volunteers serving meals to Filipino children in Cebu',
                'category'   => 'Field Report',
                'cat_class'  => 'cat-field',
                'date'       => 'March 15, 2025',
                'read_time'  => '5 min read',
                'title'      => '2,400 Meals Served in February — Our Biggest Month Yet',
                'excerpt'    => "Pastor Esther's team reached 18 barangays in Cebu last month, feeding more children than any single month since the program launched in 2022. The momentum is real — and so is the hunger. Here's a full breakdown of where every meal went and who made it possible.",
                'cta_label'  => 'Read Full Report →',
                'cta_href'   => '#',
                'delay'      => '',
            ],
            [
                'featured'   => false,
                'categories' => 'field-reports impact-stories',
                'country'    => 'Uganda',
                'flag'       => '🇺🇬',
                'image'      => 'images/landingpage/clean_drink_water.webp',
                'img_alt'    => 'Clean water flowing from a newly completed well in Uganda',
                'category'   => 'Field Report',
                'cat_class'  => 'cat-field',
                'date'       => 'February 28, 2025',
                'read_time'  => '4 min read',
                'title'      => 'Well #7 is Complete — 200 Families Now Have Clean Water',
                'excerpt'    => "After four months of drilling and community preparation, Well #7 in Mbale, Uganda was activated. We were there to witness the first water flowing — and the celebration that followed was unlike anything we've seen.",
                'cta_label'  => 'Read the Story',
                'cta_href'   => '#',
                'delay'      => 'delay-1',
            ],
            [
                'featured'   => false,
                'categories' => 'impact-stories',
                'country'    => 'Philippines',
                'flag'       => '🇵🇭',
                'image'      => 'images/landingpage/community_outreach.webp',
                'img_alt'    => 'Community outreach program helping children in the Philippines',
                'category'   => 'Impact Story',
                'cat_class'  => 'cat-impact',
                'date'       => 'February 10, 2025',
                'read_time'  => '6 min read',
                'title'      => "Marco's Story: From Malnourished to Thriving in 6 Months",
                'excerpt'    => "When Marco first came to our feeding program, he hadn't eaten a full meal in two days. Six months later, he attends school every day and his health has completely transformed. This is why we do what we do.",
                'cta_label'  => "Read Marco's Story",
                'cta_href'   => '#',
                'delay'      => 'delay-2',
            ],
            [
                'featured'   => false,
                'categories' => 'field-reports',
                'country'    => 'Philippines',
                'flag'       => '🇵🇭',
                'image'      => 'images/landingpage/medical_care.webp',
                'img_alt'    => 'Medical volunteers providing care to patients in Leyte, Philippines',
                'category'   => 'Medical',
                'cat_class'  => 'cat-medical',
                'date'       => 'January 22, 2025',
                'read_time'  => '5 min read',
                'title'      => 'Medical Mission to Leyte: 340 Patients in 3 Days',
                'excerpt'    => 'Our volunteer medical team traveled to Leyte for a three-day free clinic. Over 340 patients received checkups, prescriptions, and surgery referrals — many seeing a doctor for the first time in years.',
                'cta_label'  => 'Read the Report',
                'cta_href'   => '#',
                'delay'      => 'delay-1',
            ],
            [
                'featured'   => false,
                'categories' => 'disaster-response field-reports',
                'country'    => 'Philippines',
                'flag'       => '🇵🇭',
                'image'      => 'images/landingpage/sex_trafficking.webp',
                'img_alt'    => 'Anti-trafficking outreach and partnership expansion in the Philippines',
                'category'   => 'Anti-Trafficking',
                'cat_class'  => 'cat-trafficking',
                'date'       => 'January 5, 2025',
                'read_time'  => '4 min read',
                'title'      => 'Partnership Expands: 3 New Rescue Organizations Join JDGM Network',
                'excerpt'    => 'Our anti-trafficking work now reaches three more provinces following partnerships with two established Philippine NGOs focused on survivor rehabilitation. This is what collaboration for justice looks like.',
                'cta_label'  => 'Read the Announcement',
                'cta_href'   => '#',
                'delay'      => 'delay-2',
            ],
            [
                'featured'   => false,
                'categories' => 'impact-stories',
                'country'    => 'Philippines',
                'flag'       => '🇵🇭',
                'image'      => 'images/landingpage/education.webp',
                'img_alt'    => 'Children enrolled in the JDGM education sponsorship program',
                'category'   => 'Education',
                'cat_class'  => 'cat-education',
                'date'       => 'December 18, 2024',
                'read_time'  => '3 min read',
                'title'      => '18 Children Enrolled in Education Sponsorship for 2025',
                'excerpt'    => 'Thanks to generous donors, 18 children who would have otherwise dropped out of school will have their tuition, uniforms, and school supplies covered for the entire year. Your giving made every enrollment possible.',
                'cta_label'  => 'Sponsor a Child',
                'cta_href'   => 'donate',
                'delay'      => 'delay-3',
            ],
        ];
    }
}
