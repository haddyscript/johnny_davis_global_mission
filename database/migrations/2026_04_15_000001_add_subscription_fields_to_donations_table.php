<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            // Stripe Customer ID — reused across renewals for this donor
            $table->string('stripe_customer_id')->nullable()->after('transaction_id')
                ->comment('Stripe Customer ID (cus_...) created for recurring billing');

            // Subscription reference — Stripe sub_... or PayPal I-... ID
            $table->string('subscription_id')->nullable()->unique()->after('stripe_customer_id')
                ->comment('Stripe Subscription ID (sub_...) or PayPal Subscription ID (I-...)');

            // Subscription lifecycle status — separate from payment status
            $table->string('subscription_status')->nullable()->after('subscription_id')
                ->comment('active, past_due, cancelled, pending — mirrors gateway status');

            // When the next charge is scheduled
            $table->timestamp('next_billing_date')->nullable()->after('subscription_status')
                ->comment('Next renewal date for monthly subscriptions');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_customer_id',
                'subscription_id',
                'subscription_status',
                'next_billing_date',
            ]);
        });
    }
};
