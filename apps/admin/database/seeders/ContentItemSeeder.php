<?php

namespace Database\Seeders;

use App\Models\ContentItem;
use App\Models\Location;
use Illuminate\Database\Seeder;

class ContentItemSeeder extends Seeder
{
    public function run(): void
    {
        // Load slug → id map for location filtering
        $locationMap = Location::pluck('id', 'slug')->all();

        $jsonPath = base_path('../../apps/web/public/content.json');
        if (! file_exists($jsonPath)) {
            $this->command->warn("content.json not found at {$jsonPath}, skipping ContentItemSeeder.");
            return;
        }

        $feed = json_decode(file_get_contents($jsonPath), true);

        ContentItem::truncate();

        foreach ($feed['items'] as $sortOrder => $raw) {
            $this->importItem($raw, $sortOrder, null, $locationMap);
        }
    }

    private function importItem(array $raw, int $sortOrder, ?int $parentId, array $locationMap): void
    {
        $item = ContentItem::create([
            'type'          => $raw['type'],
            'parent_id'     => $parentId,
            'sort_order'    => $sortOrder,
            'title'         => $raw['title'] ?? null,
            'description'   => $raw['description'] ?? null,
            'content'       => $raw['content'] ?? null,
            // JSON uses 'thumbnail'; DB uses thumbnail_url
            'thumbnail_url' => $raw['thumbnail'] ?? null,
            // JSON uses 'image'; DB uses image_url
            'image_url'     => $raw['image'] ?? null,
            'date'          => $raw['date'] ?? null,
            'time'          => $raw['time'] ?? null,
            'link_url'      => $raw['link'] ?? null,
            // JSON uses 'cta'; DB uses link_text
            'link_text'     => $raw['cta'] ?? null,
            'published'     => true,
        ]);

        // Attach location restrictions from the JSON 'locations' slug array
        if (! empty($raw['locations'])) {
            $ids = array_filter(array_map(
                fn ($slug) => $locationMap[$slug] ?? null,
                $raw['locations']
            ));
            $item->locations()->sync($ids);
        }

        // Recurse into group children
        if ($raw['type'] === 'group' && ! empty($raw['items'])) {
            foreach ($raw['items'] as $childOrder => $child) {
                $this->importItem($child, $childOrder, $item->id, $locationMap);
            }
        }
    }
}
