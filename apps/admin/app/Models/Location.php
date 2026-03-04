<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'description',
        'logo_path',
        'primary_color',
        'primary_light_color',
        'primary_dark_color',
        'accent_color',
        'accent_light_color',
        'accent_dark_color',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function contentItems(): BelongsToMany
    {
        return $this->belongsToMany(ContentItem::class, 'content_item_locations');
    }

    /** Returns the data shaped like page.json for the public API. */
    public function toPageConfig(): array
    {
        return [
            'logo'        => $this->logo_path,
            'title'       => $this->title,
            'description' => $this->description,
            'theme'       => [
                'primaryColor'      => $this->primary_color,
                'primaryLightColor' => $this->primary_light_color,
                'primaryDarkColor'  => $this->primary_dark_color,
                'accentColor'       => $this->accent_color,
                'accentLightColor'  => $this->accent_light_color,
                'accentDarkColor'   => $this->accent_dark_color,
            ],
        ];
    }
}
