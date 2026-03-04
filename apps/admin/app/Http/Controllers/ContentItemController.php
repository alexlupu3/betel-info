<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContentItemRequest;
use App\Http\Requests\UpdateContentItemRequest;
use App\Models\ContentItem;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ContentItemController extends Controller
{
    public function index(): View
    {
        $items = ContentItem::with(['children.locations', 'locations'])
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('content-items.index', compact('items'));
    }

    public function create(Request $request): View
    {
        $locations  = Location::where('is_default', false)->orderBy('title')->get();
        $groups     = ContentItem::where('type', 'group')->orderBy('title')->get();
        $parentId   = $request->query('parent_id');

        return view('content-items.create', compact('locations', 'groups', 'parentId'));
    }

    public function store(StoreContentItemRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $locationIds = $data['locations'] ?? [];
        unset($data['locations']);

        // Auto-assign sort_order
        $maxOrder = ContentItem::where('parent_id', $data['parent_id'] ?? null)->max('sort_order') ?? -1;
        $data['sort_order'] = $maxOrder + 1;

        $item = ContentItem::create($data);
        $item->locations()->sync($locationIds);

        return redirect()->route('content-items.index')
            ->with('status', 'item-created');
    }

    public function edit(ContentItem $contentItem): View
    {
        $locations = Location::where('is_default', false)->orderBy('title')->get();
        $groups    = ContentItem::where('type', 'group')
            ->where('id', '!=', $contentItem->id)
            ->orderBy('title')
            ->get();

        $contentItem->load('locations');

        return view('content-items.edit', compact('contentItem', 'locations', 'groups'));
    }

    public function update(UpdateContentItemRequest $request, ContentItem $contentItem): RedirectResponse
    {
        $data = $request->validated();
        $locationIds = $data['locations'] ?? [];
        unset($data['locations']);

        $contentItem->update($data);
        $contentItem->locations()->sync($locationIds);

        return redirect()->route('content-items.index')
            ->with('status', 'item-updated');
    }

    public function destroy(ContentItem $contentItem): RedirectResponse
    {
        $contentItem->delete();

        return redirect()->route('content-items.index')
            ->with('status', 'item-deleted');
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'items'             => ['required', 'array'],
            'items.*.id'        => ['required', 'integer', 'exists:content_items,id'],
            'items.*.sort_order'=> ['required', 'integer'],
            'items.*.parent_id' => ['nullable', 'integer', 'exists:content_items,id'],
        ]);

        foreach ($request->input('items') as $row) {
            ContentItem::where('id', $row['id'])->update([
                'sort_order' => $row['sort_order'],
                'parent_id'  => $row['parent_id'] ?? null,
            ]);
        }

        return response()->json(['ok' => true]);
    }
}
