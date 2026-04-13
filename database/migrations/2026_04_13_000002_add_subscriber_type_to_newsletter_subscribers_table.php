<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            // 'normal'   = newsletter-only signup
            // 'one_time' = made a one-time donation
            // 'monthly'  = made a recurring/monthly donation
            $table->string('subscriber_type', 20)->default('normal')->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropColumn('subscriber_type');
        });
    }
};
