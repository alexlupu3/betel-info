<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\JsonResponse;

class PageConfigController extends Controller
{
    /**
     * Return the page config for a given location slug.
     * If no slug is provided, the default location is returned.
     */
    public function show(?string $slug = null): JsonResponse
    {
        $location = $slug
            ? Location::where('slug', $slug)->firstOrFail()
            : Location::where('is_default', true)->firstOrFail();

        return response()->json($location->toPageConfig());
    }
}
