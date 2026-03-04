<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use App\Models\Location;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    public function index(?string $slug = null): JsonResponse
    {
        $location = $slug
            ? Location::where('slug', $slug)->firstOrFail()
            : null;

        // Reusable closure: exclude expired dated cards
        $notExpired = fn ($q) => $q->where(function ($q) {
            $q->where('type', '!=', 'card')
              ->orWhereNull('date')
              ->orWhereDate('date', '>=', today());
        });

        // Reusable closure: filter by location when a slug was given;
        // no slug = show everything regardless of location assignments
        $visibleAt = $location
            ? (fn ($q) => $q->where(function ($q) use ($location) {
                $q->whereDoesntHave('locations')
                  ->orWhereHas('locations', fn ($q) => $q->where('locations.id', $location->id));
              }))
            : fn ($q) => $q; // no-op

        $items = ContentItem::with([
                'locations',
                'children' => fn ($q) => $q
                    ->where('published', true)
                    ->tap($notExpired)
                    ->tap($visibleAt)
                    ->orderBy('sort_order'),
                'children.locations',
            ])
            ->whereNull('parent_id')
            ->where('published', true)
            ->tap($notExpired)
            ->tap($visibleAt)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (ContentItem $item) => $item->toApiArray())
            ->values()
            ->all();

        return response()->json(['items' => $items]);
    }
}
