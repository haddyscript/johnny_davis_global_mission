<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [

            // ─────────────────────────────────────────────────
            // 1. Broadcast / Newsletter
            // ─────────────────────────────────────────────────
            [
                'name'        => 'Broadcast Email',
                'type'        => 'broadcast',
                'description' => 'Used for sending announcements and ministry updates to newsletter subscribers.',
                'subject'     => 'Latest Update from Johnny Davis Ministry',
                'variables'   => [
                    'name'             => 'Subscriber\'s full name',
                    'email'            => 'Subscriber\'s email address',
                    'message'          => 'Main announcement or update content',
                    'unsubscribe_link' => 'Auto-generated signed URL — injected automatically by the system. Always available in the template body as {{unsubscribe_link}}.',
                ],
                'body' => <<<HTML
<h2>Dear {{name}},</h2>

<p>Thank you for being a valued part of the Johnny Davis Global Missions community. We are excited to share the latest updates straight from the heart of our ministry.</p>

<p>{{message}}</p>

<h3>Stay Connected</h3>
<p>Your continued prayers and support make everything we do possible. Together, we are reaching the unreachable and feeding the hungry — both physically and spiritually.</p>

<p>God bless you abundantly,<br>
<strong>Pastor Johnny Davis</strong><br>
Johnny Davis Global Missions</p>
HTML,
            ],

            // ─────────────────────────────────────────────────
            // 2. Donation Confirmation (Immediate)
            // ─────────────────────────────────────────────────
            [
                'name'        => 'Donation Confirmation',
                'type'        => 'donation_immediate',
                'description' => 'Sent instantly after a donor completes their donation.',
                'subject'     => 'Thank You for Your Generous Gift',
                'variables'   => [
                    'donor_name'      => 'Donor\'s full name',
                    'donation_amount' => 'Amount donated (e.g. $50.00)',
                    'donation_date'   => 'Date the donation was made',
                    'transaction_id'  => 'Unique transaction reference ID',
                ],
                'body' => <<<HTML
<h2>Thank You, {{donor_name}}! 🙏</h2>

<p>Your generous gift has been received and we are deeply grateful. Here are your donation details:</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;">
    <tr style="background:#f0fdf9;">
        <td style="padding:12px 16px;border:1px solid #d1fae5;font-weight:600;width:40%;">Donor Name</td>
        <td style="padding:12px 16px;border:1px solid #d1fae5;">{{donor_name}}</td>
    </tr>
    <tr>
        <td style="padding:12px 16px;border:1px solid #e8e8ea;font-weight:600;background:#f8fafc;">Donation Amount</td>
        <td style="padding:12px 16px;border:1px solid #e8e8ea;font-size:18px;font-weight:700;color:#0f766e;">{{donation_amount}}</td>
    </tr>
    <tr style="background:#f0fdf9;">
        <td style="padding:12px 16px;border:1px solid #d1fae5;font-weight:600;">Donation Date</td>
        <td style="padding:12px 16px;border:1px solid #d1fae5;">{{donation_date}}</td>
    </tr>
    <tr>
        <td style="padding:12px 16px;border:1px solid #e8e8ea;font-weight:600;background:#f8fafc;">Transaction ID</td>
        <td style="padding:12px 16px;border:1px solid #e8e8ea;font-family:monospace;font-size:13px;color:#667085;">{{transaction_id}}</td>
    </tr>
</table>

<p>Your gift is already making a difference. Every dollar goes directly toward feeding families, sharing the Gospel, and transforming communities across the globe.</p>

<p>A receipt has been included with this email for your records. Please keep it for tax purposes.</p>

<p>With gratitude,<br>
<strong>Pastor Johnny Davis</strong><br>
Johnny Davis Global Missions</p>
HTML,
            ],

            // ─────────────────────────────────────────────────
            // 3. Day 3 Follow-up
            // ─────────────────────────────────────────────────
            [
                'name'        => 'Donation Follow-up Day 3',
                'type'        => 'donation_day_3',
                'description' => 'Sent 3 days after a donation — shares the mission story and how the gift is helping.',
                'subject'     => 'The Story Behind the Meals You\'re Funding 🍽️',
                'variables'   => [
                    'donor_name'    => 'Donor\'s full name',
                    'impact_story'  => 'A real story of impact from the ministry',
                    'ministry_link' => 'Link to learn more about the ministry',
                ],
                'body' => <<<HTML
<h2>Dear {{donor_name}},</h2>

<p>Three days ago, you made a decision that is already changing lives. We wanted to share a story from the field that shows exactly what your generosity is doing.</p>

<blockquote>{{impact_story}}</blockquote>

<p>This is the reality of what your donation makes possible — every single day. Families who had nothing now have food on their table and hope in their hearts.</p>

<h3>The Mission</h3>
<p>Johnny Davis Global Missions exists to reach the hungry, the lost, and the forgotten — physically and spiritually. We operate feeding programs, evangelism outreaches, and community development projects across multiple nations.</p>

<p>
    <a href="{{ministry_link}}"
       style="display:inline-block;background:#0f766e;color:#ffffff;padding:14px 28px;border-radius:10px;text-decoration:none;font-weight:700;font-size:15px;">
        Learn More About the Ministry →
    </a>
</p>

<p>Thank you for standing with us.<br>
<strong>Pastor Johnny Davis</strong></p>
HTML,
            ],

            // ─────────────────────────────────────────────────
            // 4. Day 14 Impact Update
            // ─────────────────────────────────────────────────
            [
                'name'        => 'Donation Impact Update Day 14',
                'type'        => 'donation_day_14',
                'description' => 'Sent 14 days after donation — shows real-world impact and progress updates.',
                'subject'     => 'Here\'s the Real-World Impact of Your Gift So Far',
                'variables'   => [
                    'donor_name'    => 'Donor\'s full name',
                    'impact_update' => 'Specific impact metrics or field update',
                    'photos_link'   => 'Link to photos or video from the field',
                ],
                'body' => <<<HTML
<h2>Two Weeks In — Your Gift Is at Work, {{donor_name}}</h2>

<p>It has been 14 days since your generous donation, and we wanted to give you a real, honest update on what has happened because of it.</p>

<h3>Impact Update</h3>
<p>{{impact_update}}</p>

<p>These are not just numbers — these are real people, real families, with real names and real stories. And you are a part of every single one.</p>

<h3>See It With Your Own Eyes</h3>
<p>We have captured moments from the field so you can see your impact visually.</p>

<p>
    <a href="{{photos_link}}"
       style="display:inline-block;background:#0f766e;color:#ffffff;padding:14px 28px;border-radius:10px;text-decoration:none;font-weight:700;font-size:15px;">
        View Field Photos & Updates →
    </a>
</p>

<p>Your partnership means the world to us and to every family we serve.<br>
<strong>Pastor Johnny Davis</strong><br>
Johnny Davis Global Missions</p>
HTML,
            ],

            // ─────────────────────────────────────────────────
            // 5. Day 30 Personal Update
            // ─────────────────────────────────────────────────
            [
                'name'        => '30-Day Personal Update',
                'type'        => 'donation_day_30',
                'description' => 'A personal message from Pastor Johnny sent 30 days after the donation.',
                'subject'     => '30 Days In — A Personal Update from Pastor Johnny',
                'variables'   => [
                    'donor_name'     => 'Donor\'s full name',
                    'pastor_message' => 'Personal message written by Pastor Johnny',
                    'video_link'     => 'Link to a personal video message',
                ],
                'body' => <<<HTML
<h2>A Personal Note for You, {{donor_name}}</h2>

<p>I wanted to take a moment — personally — to reach out to you.</p>

<p>One month ago, you chose to invest in this ministry. And today, I sit here humbled by what God has done with your gift.</p>

<p>{{pastor_message}}</p>

<p>I recorded a short personal video message specifically for our donors this month. I want you to hear directly from me about what God is doing:</p>

<p>
    <a href="{{video_link}}"
       style="display:inline-block;background:#0f172a;color:#ffffff;padding:14px 28px;border-radius:10px;text-decoration:none;font-weight:700;font-size:15px;">
        ▶ Watch Pastor Johnny's Message
    </a>
</p>

<p>Thank you from the bottom of my heart. You are not just a donor — you are a co-laborer in the harvest.</p>

<p>In His service,<br>
<strong>Pastor Johnny Davis</strong><br>
Johnny Davis Global Missions</p>
HTML,
            ],

            // ─────────────────────────────────────────────────
            // 6. Payment Follow-up (Pending / Failed)
            // ─────────────────────────────────────────────────
            [
                'name'        => 'Payment Follow-up',
                'type'        => 'payment_followup',
                'description' => 'Sent manually by an admin when a donation is pending or failed — encourages the donor to complete their payment.',
                'subject'     => 'Action Required: Your Donation to {{campaign_name}} Did Not Complete',
                'variables'   => [
                    'donor_name'      => 'Donor\'s full name',
                    'campaign_name'   => 'Name of the campaign the donation was for',
                    'donation_amount' => 'Amount of the donation (e.g. $29.99)',
                    'donate_link'     => 'Link to the donation page',
                ],
                'body' => <<<HTML
<h2>Hi {{donor_name}},</h2>

<p>We noticed that your recent donation of <strong>{{donation_amount}}</strong> toward <strong>{{campaign_name}}</strong> did not complete successfully.</p>

<p>This can happen due to a declined card, expired payment details, or a temporary network issue. There is nothing to worry about — completing your donation only takes a moment.</p>

<div style="background:#f0fdf9;border-left:4px solid #0f766e;padding:16px 20px;border-radius:0 8px 8px 0;margin:24px 0;">
    <p style="margin:0;font-weight:700;color:#0f766e;font-size:15px;">Your Donation Summary</p>
    <p style="margin:10px 0 0;color:#334155;"><strong>Campaign:</strong> {{campaign_name}}</p>
    <p style="margin:6px 0 0;color:#334155;"><strong>Amount:</strong> {{donation_amount}}</p>
</div>

<p>We would love for you to complete your gift. Every contribution — big or small — makes a real and lasting difference in the lives of the families we serve together.</p>

<p>
    <a href="{{donate_link}}"
       style="display:inline-block;background:#0f766e;color:#ffffff;padding:14px 32px;border-radius:10px;text-decoration:none;font-weight:700;font-size:15px;margin:8px 0;">
        Complete My Donation →
    </a>
</p>

<p>If you experienced any issues or have questions about your payment, please don't hesitate to reach out. We are here to help and we are grateful for your generosity.</p>

<p>With gratitude and blessings,<br>
<strong>Pastor Johnny Davis</strong><br>
Johnny Davis Global Missions</p>
HTML,
            ],

            // ─────────────────────────────────────────────────
            // 7. Day 45 Upgrade Ask
            // ─────────────────────────────────────────────────
            [
                'name'        => 'Donation Upgrade Ask',
                'type'        => 'donation_day_45',
                'description' => 'Sent 45 days after donation — encourages recurring giving or an upgraded donation amount.',
                'subject'     => 'Your Gift is Changing Lives — Take the Next Step',
                'variables'   => [
                    'donor_name'            => 'Donor\'s full name',
                    'impact_summary'        => 'Summary of the impact their donation has had',
                    'donation_upgrade_link' => 'Link to the donation upgrade page',
                ],
                'body' => <<<HTML
<h2>{{donor_name}}, You Have Already Changed Lives</h2>

<p>45 days ago, you made a decision that echoed into eternity. Here is a summary of the impact your gift has had:</p>

<blockquote>{{impact_summary}}</blockquote>

<p>That is the power of one gift. Imagine what happens when that gift grows.</p>

<h3>Take the Next Step</h3>
<p>We want to invite you to consider becoming a <strong>recurring monthly supporter</strong> or increasing your giving level. Even a small monthly commitment creates a sustained, reliable pipeline of resources that lets us plan, grow, and reach more people.</p>

<ul>
    <li><strong>$25/month</strong> — Feeds a family for a week</li>
    <li><strong>$50/month</strong> — Sponsors an evangelism outreach event</li>
    <li><strong>$100/month</strong> — Funds a month of community development work</li>
</ul>

<p>
    <a href="{{donation_upgrade_link}}"
       style="display:inline-block;background:#0f766e;color:#ffffff;padding:16px 36px;border-radius:10px;text-decoration:none;font-weight:700;font-size:16px;margin-top:8px;">
        Upgrade My Giving Today →
    </a>
</p>

<p>Every level of partnership matters. We are grateful for whatever God places on your heart.</p>

<p>With love and gratitude,<br>
<strong>Pastor Johnny Davis</strong><br>
Johnny Davis Global Missions</p>
HTML,
            ],

        ];

        foreach ($templates as $data) {
            EmailTemplate::updateOrCreate(
                ['type' => $data['type']],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
