<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageConfig extends Model
{
    protected $fillable = [
        'location_id',
        'title',
        'description',
        'logo_path',
        'primary_color',
        'primary_light_color',
        'primary_dark_color',
        'accent_color',
        'accent_light_color',
        'accent_dark_color',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
