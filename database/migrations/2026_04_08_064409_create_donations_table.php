<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            $table->string('campaign_name')->comment('e.g., Uganda Water Wells, Feed Filipino Children');

            $table->string('first_name')->comment('Donor first name, e.g., Maria');

            $table->string('last_name')->comment('Donor last name, e.g., Santos');

            $table->string('email')->comment('Contact email for receipt, e.g., maria@example.com');

            $table->decimal('amount', 15, 2)->comment('Donation value, e.g., 7.99, 29.99');

            $table->enum('frequency', ['one-time', 'monthly'])->default('one-time')->comment('Billing cycle: one-time or monthly');

            $table->string('payment_method')->comment('Source: card, gcash, or paypal');

            // Card Metadata
            $table->string('card_brand')->nullable()->comment('e.g., Visa, Mastercard');

            $table->string('card_last_four', 4)->nullable()->comment('e.g., 4242');

            $table->string('card_exp_month', 2)->nullable()->comment('MM format, e.g., 12');

            $table->string('card_exp_year', 4)->nullable()->comment('YYYY format, e.g., 2028');

            $table->string('transaction_id')->unique()->nullable()->comment('Gateway reference ID (Stripe/PayPal)');

            $table->string('status')->default('pending')->comment('Current state: pending, completed, failed');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
