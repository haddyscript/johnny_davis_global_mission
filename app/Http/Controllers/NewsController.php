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
            'description' => $cms->text('meta', 'description', 'Blog & News — Johnny Davis Global Missions. Monthly field updates, impact stories, and mission reports from the Philippines.'),
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
                'image'      => 'images/landingpage/feedthehungry.png',
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
                'categories' => 'prayer-requests disaster-response',
                'country'    => 'Philippines',
                'flag'       => '🇵🇭',
                'image'      => 'images/relevant-events/earthquake-fb-post.jpg',
                'img_alt'    => 'Pray for the Philippines — Johnny Davis Global Missions',
                'category'   => 'Prayer Request',
                'cat_class'  => 'cat-prayer',
                'date'       => 'June 2025',
                'read_time'  => '2 min read',
                'title'      => 'Prayers For Our Leaders, Volunteers & Families in the Philippines',
                'excerpt'    => 'Our hearts are with everyone affected by the recent earthquake in the Philippines. To our leaders, volunteers, and families in General Santos City, Sarangani Province, Leyte, Cebu, and throughout the Philippines — you are in our prayers during this time.',
                'full_content' =>
                    '<p>Our hearts are with everyone affected by the recent earthquake in the Philippines.</p>'
                    . '<p>To our leaders, volunteers, and families in General Santos City, Sarangani Province, Leyte, Cebu, and throughout the Philippines, please know that you are in our prayers during this time.</p>'
                    . '<p>Current reports indicate that much of the damage has occurred in the General Santos and Sarangani region. We are praying for every family affected, for emergency responders, and for God\'s protection, comfort, and peace over your communities.</p>'
                    . '<p>As a ministry family, we stand together in faith. Although many miles separate us, we are united in Christ and committed to supporting one another through prayer, encouragement, and compassion.</p>'
                    . '<p>We are grateful for each of our volunteers who faithfully serve through the Feed Filipino Children Program and our outreach efforts across the Philippines. Your love for others continues to reflect the heart of Jesus, especially during challenging times.</p>'
                    . '<p>Please continue to pray for those affected by this disaster and check on family members, friends, neighbors, and fellow volunteers who may need encouragement and support.</p>'
                    . '<blockquote class="story-modal-quote">&ldquo;God is our refuge and strength, a very present help in trouble.&rdquo;<cite>&mdash; Psalm 46:1 (KJV)</cite></blockquote>'
                    . '<p>With love and prayers,</p>'
                    . '<p><strong>Johnny Davis</strong><br>President &amp; Founder</p>',
                'cta_label'  => 'Read Full Message',
                'cta_href'   => '#prayer-alert',
                'delay'      => 'delay-1',
            ],
            [
                'featured'   => false,
                'categories' => 'impact-stories',
                'country'    => 'Philippines',
                'flag'       => '🇵🇭',
                'image'      => 'images/landingpage/community_outreach.png',
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
                'image'      => 'images/landingpage/medical_care.png',
                'img_alt'    => 'Medical volunteers providing care to patients in Leyte, Philippines',
                'category'   => 'Medical',
                'cat_class'  => 'cat-medical',
                'date'       => 'May 30, 2025',
                'read_time'  => '5 min read',
                'title'      => 'Medical Mission to Cebu: 340 Patients in 3 Days After the Recent Earthquake',
                'excerpt'    => 'Compassion in Action After Cebu Earthquake. Following the 6.9 magnitude earthquake that struck Cebu on May 30, 2025, many families were left injured, scared, and in urgent need of medical care. In response, our medical team traveled to affected communities for a three-day free clinic.<br>Over 340 patients received checkups, prescriptions, and surgery referrals — many seeing a doctor for the first time in years.<br>Your support is helping bring healing and hope to Cebu when it\'s needed most.',
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
                'image'      => 'images/landingpage/education.png',
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
            [
                'featured'   => false,
                'categories' => 'field-reports philippines',
                'country'    => 'Philippines',
                'flag'       => '🇵🇭',
                'image'      => 'images/landingpage/community_outreach.png',
                'img_alt'    => 'Clean water well installation in a Cebu barangay',
                'category'   => 'Field Report',
                'cat_class'  => 'cat-field',
                'date'       => 'November 30, 2024',
                'read_time'  => '4 min read',
                'title'      => 'Clean Drinking Water Reaches 200 Families in Cebu Barangays',
                'excerpt'    => 'Before this project, families in three Cebu barangays walked over a mile each day for clean drinking water. Contaminated water caused frequent sickness, especially among children. <br> Thanks to your support, two new clean drinking water systems are now providing safe, life-giving water to 200 households. <br> As a result, cases of waterborne illness have already dropped by half.',
                'cta_label'  => 'Read the Report',
                'cta_href'   => '#',
                'delay'      => 'delay-1',
            ],
            [
                'featured'   => false,
                'categories' => 'impact-stories philippines',
                'country'    => 'Philippines',
                'flag'       => '🇵🇭',
                'image'      => 'images/landingpage/feedthehungry.png',
                'img_alt'    => 'Volunteers distributing food packages to families in Leyte',
                'category'   => 'Impact Story',
                'cat_class'  => 'cat-impact',
                'date'       => 'November 10, 2024',
                'read_time'  => '5 min read',
                'title'      => "A Mother's Letter: 'My Children Sleep Full for the First Time'",
                'excerpt'    => 'Rosa, a widow raising four children in Leyte, wrote to our team after her family joined the monthly food-pack distribution. Her words reminded every volunteer why they give their Saturdays to this mission.',
                'cta_label'  => "Read Rosa's Story",
                'cta_href'   => '#',
                'delay'      => 'delay-2',
            ],
            [
                'featured'   => false,
                'categories' => 'field-reports philippines',
                'country'    => 'Philippines',
                'flag'       => '🇵🇭',
                'image'      => 'images/landingpage/medical_care.png',
                'img_alt'    => 'JDGM medical team conducting free eye screening in Cebu',
                'category'   => 'Field Report',
                'cat_class'  => 'cat-field',
                'date'       => 'October 5, 2024',
                'read_time'  => '4 min read',
                'title'      => 'Free Eye Screening Camp: 180 Pairs of Glasses Distributed in Cebu',
                'excerpt'    => 'Partnering with a local optometry school, our team screened over 400 residents and distributed 180 prescription eyeglasses at no cost. For many elderly recipients, it was the first time they could read in years.',
                'cta_label'  => 'Read the Report',
                'cta_href'   => '#',
                'delay'      => 'delay-3',
            ],
            [
                'featured'   => false,
                'categories' => 'field-reports disaster-response philippines',
                'country'    => 'Philippines',
                'flag'       => '🇵🇭',
                'image'      => 'images/landingpage/community_outreach.png',
                'img_alt'    => 'Relief goods being distributed after Typhoon Kristine in Leyte',
                'category'   => 'Disaster Response',
                'cat_class'  => 'cat-disaster',
                'date'       => 'September 20, 2024',
                'read_time'  => '6 min read',
                'title'      => 'Typhoon Kristine Relief: 1,200 Families Received Emergency Aid in Leyte',
                'excerpt'    => 'When Typhoon Kristine made landfall in Leyte, our response team was on the ground within 48 hours. Over five days we distributed food packs, tarpaulins, and hygiene kits to 1,200 displaced families across six barangays.',
                'cta_label'  => 'Read the Full Report',
                'cta_href'   => '#',
                'delay'      => 'delay-4',
            ],
        ];
    }
}
