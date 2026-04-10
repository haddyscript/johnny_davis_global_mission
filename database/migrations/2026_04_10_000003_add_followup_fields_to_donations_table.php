<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->timestamp('follow_up_sent_at')->nullable()
                ->after('status')
                ->comment('Timestamp of the most recent follow-up email sent by an admin.');

            $table->unsignedTinyInteger('follow_up_count')->default(0)
                ->after('follow_up_sent_at')
                ->comment('Total number of follow-up emails sent for this donation.');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['follow_up_sent_at', 'follow_up_count']);
        });
    }
};
