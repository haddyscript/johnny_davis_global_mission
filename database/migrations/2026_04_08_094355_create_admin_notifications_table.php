<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type');               // new_donation, failed_donation, new_subscriber, new_contact
            $table->string('title');
            $table->string('message');
            $table->string('icon')->default('🔔');
            $table->string('icon_bg')->default('#f0fdf4');
            $table->string('icon_color')->default('#16a34a');
            $table->json('data')->nullable();      // related model id, action_url, etc.
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
