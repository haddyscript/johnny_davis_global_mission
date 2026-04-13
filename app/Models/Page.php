<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nav_item_id', 'slug', 'name', 'description', 'is_active', 'sort_order', 'metadata'])]
class Page extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'metadata'    => 'array',
            'nav_item_id' => 'integer',
        ];
    }

    public function navItem(): BelongsTo
    {
        return $this->belongsTo(NavItem::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
    }

    /**
     * The resolved URL for this page, derived from its linked nav item when present.
     */
    public function getResolvedUrlAttribute(): string
    {
        return $this->navItem?->url ?? '/' . ltrim($this->slug, '/');
    }
}
