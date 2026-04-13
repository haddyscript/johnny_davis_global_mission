<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    // Valid subscriber_type values
    const TYPE_NORMAL   = 'normal';
    const TYPE_ONE_TIME = 'one_time';
    const TYPE_MONTHLY  = 'monthly';

    protected $fillable = [
        'first_name',
        'email',
        'is_active',
        'ip_address',
        'subscriber_type',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Upsert a donor into the newsletter_subscribers table.
     * If they already exist as a normal subscriber, upgrade their type.
     * If they already exist as a donor, keep the higher type (monthly > one_time).
     */
    public static function syncDonor(string $email, string $firstName, string $frequency): self
    {
        $newType = $frequency === 'monthly' ? self::TYPE_MONTHLY : self::TYPE_ONE_TIME;

        $existing = self::where('email', $email)->first();

        if ($existing) {
            // Upgrade type if needed: monthly always wins; one_time upgrades normal
            $shouldUpgrade = ($newType === self::TYPE_MONTHLY)
                || ($newType === self::TYPE_ONE_TIME && $existing->subscriber_type === self::TYPE_NORMAL);

            $updates = ['is_active' => true];
            if ($shouldUpgrade) {
                $updates['subscriber_type'] = $newType;
            }
            $existing->update($updates);
            return $existing;
        }

        return self::create([
            'first_name'      => $firstName,
            'email'           => $email,
            'is_active'       => true,
            'subscriber_type' => $newType,
        ]);
    }
}
