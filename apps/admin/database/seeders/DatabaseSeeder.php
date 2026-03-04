<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed locations (all fields from page.json baked in)
        $locationData = [
            [
                'slug'                => 'betel-global',
                'title'               => 'Biserica Betel',
                'description'         => 'Rămâi la curent cu programul și activitățile bisericii Betel',
                'logo_path'           => '/logo.jpg',
                'primary_color'       => '#0f0f0f',
                'primary_light_color' => '#f2f2f2',
                'primary_dark_color'  => '#000000',
                'accent_color'        => '#0f0f0f',
                'accent_light_color'  => '#f2f2f2',
                'accent_dark_color'   => '#000000',
                'is_default'          => true,
            ],
            [
                'slug'                => 'betel-centru',
                'title'               => 'Betel Centru',
                'description'         => 'Rămâi la curent cu programul și activitățile Betel Centru',
                'logo_path'           => '/locations/betel-centru/logo.jpg',
                'primary_color'       => '#0f0f0f',
                'primary_light_color' => '#f2f2f2',
                'primary_dark_color'  => '#000000',
                'accent_color'        => '#ff6200',
                'accent_light_color'  => '#fff0e6',
                'accent_dark_color'   => '#cc4e00',
                'is_default'          => false,
            ],
            [
                'slug'                => 'betel-manastur',
                'title'               => 'Betel Mănăștur',
                'description'         => 'Rămâi la curent cu programul și activitățile Betel Mănăștur',
                'logo_path'           => '/locations/betel-manastur/logo.jpg',
                'primary_color'       => '#0f0f0f',
                'primary_light_color' => '#f2f2f2',
                'primary_dark_color'  => '#000000',
                'accent_color'        => '#17d3c3',
                'accent_light_color'  => '#e6faf9',
                'accent_dark_color'   => '#0fa89b',
                'is_default'          => false,
            ],
            [
                'slug'                => 'betel-vest',
                'title'               => 'Betel Vest',
                'description'         => 'Rămâi la curent cu programul și activitățile Betel Vest',
                'logo_path'           => '/locations/betel-vest/logo.jpg',
                'primary_color'       => '#0f0f0f',
                'primary_light_color' => '#f2f2f2',
                'primary_dark_color'  => '#000000',
                'accent_color'        => '#a0384b',
                'accent_light_color'  => '#f5e6e9',
                'accent_dark_color'   => '#7a2a39',
                'is_default'          => false,
            ],
            [
                'slug'                => 'betel-est',
                'title'               => 'Betel Est',
                'description'         => 'Rămâi la curent cu programul și activitățile Betel Est',
                'logo_path'           => '/locations/betel-est/logo.jpg',
                'primary_color'       => '#0f0f0f',
                'primary_light_color' => '#f2f2f2',
                'primary_dark_color'  => '#000000',
                'accent_color'        => '#ffd000',
                'accent_light_color'  => '#fffbe6',
                'accent_dark_color'   => '#ccaa00',
                'is_default'          => false,
            ],
        ];

        foreach ($locationData as $data) {
            Location::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // 2. Seed admin user from environment — both vars must be set explicitly
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

        // 3. Seed content items from content.json
        $this->call(ContentItemSeeder::class);
    }
}
