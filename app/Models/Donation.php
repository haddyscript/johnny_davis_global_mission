<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'campaign_name',
        'first_name',
        'last_name',
        'email',
        'amount',
        'frequency',
        'payment_method',
        'card_brand',
        'card_last_four',
        'card_exp_month',
        'card_exp_year',
        'transaction_id',
        'status',
        'follow_up_sent_at',
        'follow_up_count',
    ];

    protected $casts = [
        'amount'            => 'decimal:2',
        'follow_up_sent_at' => 'datetime',
        'follow_up_count'   => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    /**
     * Get the donor's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Whether this donation needs a follow-up (pending/failed and not recently sent).
     */
    public function needsFollowUp(): bool
    {
        return in_array($this->status, ['pending', 'failed'])
            && ! $this->isInFollowUpCooldown();
    }

    /**
     * Whether a follow-up was sent within the cooldown window.
     */
    public function isInFollowUpCooldown(int $hours = 24): bool
    {
        return $this->follow_up_sent_at !== null
            && $this->follow_up_sent_at->diffInHours(now()) < $hours;
    }

    /**
     * Human-readable label for the follow-up status column.
     */
    public function followUpStatusLabel(): string
    {
        if ($this->status === 'completed') {
            return 'completed';
        }

        if ($this->follow_up_count === 0) {
            return 'needs';
        }

        return $this->isInFollowUpCooldown() ? 'cooldown' : 'resend';
    }
}
