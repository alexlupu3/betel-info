<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\PageConfig;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed four locations
        $locationData = [
            ['slug' => 'betel-centru',   'name' => 'Betel Centru'],
            ['slug' => 'betel-manastur', 'name' => 'Betel Mănăștur'],
            ['slug' => 'betel-vest',     'name' => 'Betel Vest'],
            ['slug' => 'betel-est',      'name' => 'Betel Est'],
        ];

        $locations = [];
        foreach ($locationData as $data) {
            $locations[$data['slug']] = Location::updateOrCreate(
                ['slug' => $data['slug']],
                ['name' => $data['name'], 'logo_path' => "/locations/{$data['slug']}/logo.jpg"]
            );
        }

        // 2. Seed global page_config from apps/web/public/page.json
        $webPublicPath = base_path('../../apps/web/public');
        $globalJson = json_decode(file_get_contents("{$webPublicPath}/page.json"), true);

        PageConfig::updateOrCreate(
            ['location_id' => null],
            [
                'title'               => $globalJson['title'],
                'description'         => $globalJson['description'],
                'logo_path'           => $globalJson['logo'] ?? null,
                'primary_color'       => $globalJson['theme']['primaryColor'],
                'primary_light_color' => $globalJson['theme']['primaryLightColor'] ?? null,
                'primary_dark_color'  => $globalJson['theme']['primaryDarkColor'] ?? null,
                'accent_color'        => $globalJson['theme']['accentColor'] ?? null,
                'accent_light_color'  => $globalJson['theme']['accentLightColor'] ?? null,
                'accent_dark_color'   => $globalJson['theme']['accentDarkColor'] ?? null,
            ]
        );

        // 3. Seed per-location page_configs
        foreach (array_keys($locations) as $slug) {
            $jsonPath = "{$webPublicPath}/locations/{$slug}/page.json";
            if (! file_exists($jsonPath)) {
                continue;
            }
            $json = json_decode(file_get_contents($jsonPath), true);

            PageConfig::updateOrCreate(
                ['location_id' => $locations[$slug]->id],
                [
                    'title'               => $json['title'],
                    'description'         => $json['description'],
                    'logo_path'           => $json['logo'] ?? null,
                    'primary_color'       => $json['theme']['primaryColor'],
                    'primary_light_color' => $json['theme']['primaryLightColor'] ?? null,
                    'primary_dark_color'  => $json['theme']['primaryDarkColor'] ?? null,
                    'accent_color'        => $json['theme']['accentColor'] ?? null,
                    'accent_light_color'  => $json['theme']['accentLightColor'] ?? null,
                    'accent_dark_color'   => $json['theme']['accentDarkColor'] ?? null,
                ]
            );
        }

        // 4. Seed admin user from environment — both vars must be set explicitly
        $adminEmail    = env('DB_ADMIN_EMAIL');
        $adminPassword = env('DB_ADMIN_PASSWORD');

        if (blank($adminEmail) || blank($adminPassword)) {
            throw new \RuntimeException(
                'DB_ADMIN_EMAIL and DB_ADMIN_PASSWORD must be set in .env before seeding.'
            );
        }

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name'     => 'Admin',
                'password' => Hash::make($adminPassword),
                'role'     => 'admin',
            ]
        );
    }
}
