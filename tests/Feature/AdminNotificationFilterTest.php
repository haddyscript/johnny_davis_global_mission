<?php

namespace Tests\Feature;

use App\Models\AdminNotification;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNotificationFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // DonationObserver raises one notification per donation
        Donation::factory()->create(['first_name' => 'Read', 'last_name' => 'Donor']);
        Donation::factory()->create(['first_name' => 'Unread', 'last_name' => 'Donor']);
        AdminNotification::where('message', 'like', '% from Read Donor')->update(['is_read' => true]);

        $this->actingAs(User::factory()->create());
    }

    public function test_all_status_filter_shows_read_and_unread_notifications(): void
    {
        $response = $this->get(route('admin.notifications.index', ['type' => '', 'unread' => '']));

        $response->assertOk();
        $response->assertSee('from Read Donor');
        $response->assertSee('from Unread Donor');
        $response->assertSee('data-target="2"', false);
    }

    public function test_unread_only_filter_hides_read_notifications(): void
    {
        $response = $this->get(route('admin.notifications.index', ['unread' => '1']));

        $response->assertOk();
        $response->assertSee('from Unread Donor');
        $response->assertDontSee('from Read Donor');
    }

    public function test_summary_counts_ignore_the_active_filter(): void
    {
        $response = $this->get(route('admin.notifications.index', ['unread' => '1']));

        $response->assertSeeInOrder([
            'data-target="2"', 'Total',
            'data-target="1"', 'Unread',
            'data-target="1"', 'Read',
        ], false);
    }
}
