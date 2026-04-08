<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'icon_bg',
        'icon_color',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data'     => 'array',
        'is_read'  => 'boolean',
        'read_at'  => 'datetime',
    ];

    /* ── Factory helpers ── */

    public static function newDonation(Donation $donation): self
    {
        return self::create([
            'type'       => 'new_donation',
            'title'      => 'New Donation Received',
            'message'    => '$' . number_format($donation->amount, 2) . ' from ' . $donation->full_name,
            'icon'       => '💰',
            'icon_bg'    => '#f0fdf4',
            'icon_color' => '#16a34a',
            'data'       => [
                'id'         => $donation->id,
                'donor'      => $donation->full_name,
                'amount'     => $donation->amount,
                'action_url' => '/admin/donations',
            ],
        ]);
    }

    public static function failedDonation(Donation $donation): self
    {
        return self::create([
            'type'       => 'failed_donation',
            'title'      => 'Payment Failed',
            'message'    => 'Donation from ' . $donation->full_name . ' could not be processed.',
            'icon'       => '❌',
            'icon_bg'    => '#fef2f2',
            'icon_color' => '#dc2626',
            'data'       => [
                'id'         => $donation->id,
                'donor'      => $donation->full_name,
                'action_url' => '/admin/donations',
            ],
        ]);
    }

    public static function pendingDonation(Donation $donation): self
    {
        return self::create([
            'type'       => 'pending_donation',
            'title'      => 'Pending Donation',
            'message'    => '$' . number_format($donation->amount, 2) . ' from ' . $donation->full_name . ' awaiting confirmation.',
            'icon'       => '⏳',
            'icon_bg'    => '#fffbeb',
            'icon_color' => '#d97706',
            'data'       => [
                'id'         => $donation->id,
                'donor'      => $donation->full_name,
                'action_url' => '/admin/donations',
            ],
        ]);
    }

    public static function newSubscriber(NewsletterSubscriber $subscriber): self
    {
        return self::create([
            'type'       => 'new_subscriber',
            'title'      => 'New Newsletter Subscriber',
            'message'    => $subscriber->first_name . ' (' . $subscriber->email . ') joined the newsletter.',
            'icon'       => '📧',
            'icon_bg'    => '#eff6ff',
            'icon_color' => '#1d4ed8',
            'data'       => [
                'id'         => $subscriber->id,
                'email'      => $subscriber->email,
                'action_url' => '/admin/subscribers',
            ],
        ]);
    }

    public static function newContactMessage(ContactMessage $message): self
    {
        return self::create([
            'type'       => 'new_contact',
            'title'      => 'New Contact Message',
            'message'    => 'From ' . $message->full_name . ': ' . \Str::limit($message->message, 60),
            'icon'       => '✉️',
            'icon_bg'    => '#fdf4ff',
            'icon_color' => '#9333ea',
            'data'       => [
                'id'         => $message->id,
                'sender'     => $message->full_name,
                'action_url' => '/admin/contact-messages/' . $message->id,
            ],
        ]);
    }

    /* ── Scopes ── */

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRecent($query, int $limit = 15)
    {
        return $query->latest()->limit($limit);
    }
}
