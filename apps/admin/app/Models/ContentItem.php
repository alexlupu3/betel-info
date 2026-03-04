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

    /** Serialise to the JSON shape the web app expects (mirrors content.json). */
    public function toApiArray(): array
    {
        $data = ['type' => $this->type];

        switch ($this->type) {
            case 'richtext':
                $data['content'] = $this->content;
                break;

            case 'card':
                $data['title'] = $this->title;
                $data['description'] = $this->description;
                if ($this->thumbnail_url) $data['thumbnail'] = $this->thumbnail_url;
                if ($this->date)          $data['date']      = $this->date->toDateString();
                if ($this->time)          $data['time']      = $this->time;
                if ($this->link_url)      $data['link']      = $this->link_url;
                if ($this->link_text)     $data['cta']       = $this->link_text;
                break;

            case 'poster':
                $data['title'] = $this->title;
                $data['image'] = $this->image_url;
                if ($this->link_url) $data['link'] = $this->link_url;
                break;

            case 'group':
                if ($this->title) $data['title'] = $this->title;
                $data['items'] = $this->children->map(fn ($child) => $child->toApiArray())->values()->all();
                break;
        }

        // Include location slugs only when the item is restricted to specific locations
        if ($this->relationLoaded('locations') && $this->locations->isNotEmpty()) {
            $data['locations'] = $this->locations->pluck('slug')->all();
        }

        return $data;
    }
}
