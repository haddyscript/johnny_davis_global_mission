<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            // When Stripe last reported a failed monthly charge (past_due / unpaid), e.g. an expired or declined card
            $table->timestamp('payment_failed_at')->nullable()->after('subscription_canceled_at')
                ->comment('When the subscription last went past_due or unpaid');

            // When a failing subscription was paid again and returned to active
            $table->timestamp('subscription_recovered_at')->nullable()->after('payment_failed_at')
                ->comment('When the subscription last recovered from past_due or unpaid to active');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['payment_failed_at', 'subscription_recovered_at']);
        });
    }
};
