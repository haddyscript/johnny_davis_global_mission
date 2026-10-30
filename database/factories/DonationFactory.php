<?php

namespace Database\Factories;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state: a completed one-time card gift.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'campaign_name' => 'Feed Filipino Children',
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'amount' => '25.00',
            'frequency' => 'one-time',
            'payment_method' => 'card',
            'transaction_id' => 'pi_'.Str::random(24),
            'status' => 'completed',
            'livemode' => false,
            'source' => 'website',
        ];
    }

    /**
     * A $7.99 Stripe monthly partner whose subscription is active.
     */
    public function monthlyPartner(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => '7.99',
            'frequency' => 'monthly',
            'transaction_id' => 'in_'.Str::random(24),
            'stripe_customer_id' => 'cus_'.Str::random(14),
            'subscription_id' => 'sub_'.Str::random(24),
            'subscription_status' => 'active',
            'next_billing_date' => now()->addMonth(),
        ]);
    }

    /**
     * A monthly partner whose latest renewal payment failed.
     */
    public function pastDue(): static
    {
        return $this->monthlyPartner()->state(fn (array $attributes) => [
            'subscription_status' => 'past_due',
        ]);
    }

    /**
     * A monthly partner who has canceled.
     */
    public function canceled(): static
    {
        return $this->monthlyPartner()->state(fn (array $attributes) => [
            'subscription_status' => 'canceled',
            'subscription_canceled_at' => now()->subDay(),
            'next_billing_date' => null,
        ]);
    }

    /**
     * A monthly checkout that has been created but not yet confirmed.
     */
    public function pendingMonthlyCheckout(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => '7.99',
            'frequency' => 'monthly',
            'transaction_id' => null,
            'stripe_customer_id' => 'cus_'.Str::random(14),
            'subscription_id' => null,
            'subscription_status' => null,
            'status' => 'pending',
        ]);
    }

    /**
     * Created with Stripe live-mode keys.
     */
    public function live(): static
    {
        return $this->state(fn (array $attributes) => ['livemode' => true]);
    }
}
