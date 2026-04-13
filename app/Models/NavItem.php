<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NavItem extends Model
{
    public function page(): HasOne
    {
        return $this->hasOne(Page::class);
    }

    protected $fillable = [
        'label',
        'url',
        'nav_class',
        'is_external',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_external' => 'boolean',
        'is_active'   => 'boolean',
    ];

    /** All active items ordered for front-end rendering. */
    public static function forNav(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('is_active', true)->orderBy('sort_order')->get();
    }
}
