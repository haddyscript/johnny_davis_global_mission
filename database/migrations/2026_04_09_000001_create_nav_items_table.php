<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('nav_items')) {
            return;
        }

        Schema::create('nav_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');                        // Display text
            $table->string('url');                          // e.g. /donate or #mission
            $table->string('nav_class')->nullable();        // e.g. btn-nav-donate
            $table->boolean('is_external')->default(false); // open_in_new_tab
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_items');
    }
};
