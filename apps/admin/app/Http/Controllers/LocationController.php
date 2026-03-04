<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $locations = Location::orderByDesc('is_default')->orderBy('title')->get();

        return view('locations.index', compact('locations'));
    }

    public function create(): View
    {
        return view('locations.create');
    }

    public function store(StoreLocationRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            if ($request->boolean('is_default')) {
                Location::where('is_default', true)->update(['is_default' => false]);
            }
            Location::create($request->validated());
        });

        return redirect()->route('locations.index')
            ->with('status', 'location-created');
    }

    public function edit(Location $location): View
    {
        return view('locations.edit', compact('location'));
    }

    public function update(UpdateLocationRequest $request, Location $location): RedirectResponse
    {
        DB::transaction(function () use ($request, $location) {
            if ($request->boolean('is_default') && ! $location->is_default) {
                Location::where('is_default', true)->update(['is_default' => false]);
            }
            $location->update($request->validated());
        });

        return redirect()->route('locations.index')
            ->with('status', 'location-updated');
    }

    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();

        return redirect()->route('locations.index')
            ->with('status', 'location-deleted');
    }
}
