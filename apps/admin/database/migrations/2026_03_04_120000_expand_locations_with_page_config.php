<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->text('description')->after('title')->default('');
            $table->string('primary_color', 7)->after('logo_path')->default('#000000');
            $table->string('primary_light_color', 7)->after('primary_color')->nullable();
            $table->string('primary_dark_color', 7)->after('primary_light_color')->nullable();
            $table->string('accent_color', 7)->after('primary_dark_color')->nullable();
            $table->string('accent_light_color', 7)->after('accent_color')->nullable();
            $table->string('accent_dark_color', 7)->after('accent_light_color')->nullable();
            $table->boolean('is_default')->default(false)->after('accent_dark_color');
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'primary_color',
                'primary_light_color',
                'primary_dark_color',
                'accent_color',
                'accent_light_color',
                'accent_dark_color',
                'is_default',
            ]);
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
        });
    }
};
