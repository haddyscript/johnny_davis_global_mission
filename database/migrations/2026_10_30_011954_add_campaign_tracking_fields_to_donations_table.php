<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            // Stripe "livemode" flag — keeps test-mode gifts out of production reporting
            $table->boolean('livemode')->nullable()->after('next_billing_date')
                ->comment('true = Stripe live mode, false = Stripe test mode, null = not verified yet');

            // Marketing source the donor arrived from (?src=qr, ?utm_source=facebook, ...)
            $table->string('source', 50)->nullable()->after('livemode')
                ->comment('Campaign source, e.g. qr, website, facebook, email');

            // When the gateway reported the subscription as canceled
            $table->timestamp('subscription_canceled_at')->nullable()->after('source')
                ->comment('When the monthly subscription was canceled');

            $table->index(['frequency', 'subscription_status', 'livemode'], 'donations_partner_lookup_index');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropIndex('donations_partner_lookup_index');
            $table->dropColumn(['livemode', 'source', 'subscription_canceled_at']);
        });
    }
};
