<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('icon', 10)->default('🎯');
            $table->string('label')->default('Active Campaign');
            $table->string('status_class')->default('active-campaign')->comment('CSS modifier class');
            $table->string('goal_amount')->default('$0');
            $table->unsignedTinyInteger('goal_pct')->default(0)->comment('0–100 progress percentage');
            $table->string('raised_amount')->nullable();
            $table->string('bar_style')->nullable()->comment('Inline CSS override for progress bar');
            $table->string('subtitle')->nullable()->comment('Short tagline shown below title');
            $table->string('meta')->nullable()->comment('Raised/goal summary line');
            $table->text('snippet')->nullable()->comment('Card description paragraph');
            $table->string('story')->nullable()->comment('Pull-quote shown on card');
            $table->text('story_full')->nullable()->comment('Full story shown in donation form');
            $table->string('goal_full')->nullable()->comment('Goal description line in donation form');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
