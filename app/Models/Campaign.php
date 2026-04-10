<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'icon',
        'label',
        'status_class',
        'goal_amount',
        'goal_pct',
        'raised_amount',
        'bar_style',
        'subtitle',
        'meta',
        'snippet',
        'story',
        'story_full',
        'goal_full',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'goal_pct'  => 'integer',
        ];
    }

    /**
     * Scope: only active campaigns in display order.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
