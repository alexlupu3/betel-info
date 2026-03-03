<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentItem extends Model
{
    protected $fillable = [
        'type',
        'parent_id',
        'sort_order',
        'title',
        'description',
        'content',
        'thumbnail_url',
        'image_url',
        'date',
        'time',
        'link_url',
        'link_text',
        'published',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'published' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ContentItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ContentItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'content_item_locations');
    }
}
