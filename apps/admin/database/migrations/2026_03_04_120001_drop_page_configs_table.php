<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('page_configs');
    }

    public function down(): void
    {
        Schema::create('page_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('logo_path')->nullable();
            $table->string('primary_color', 7);
            $table->string('primary_light_color', 7)->nullable();
            $table->string('primary_dark_color', 7)->nullable();
            $table->string('accent_color', 7)->nullable();
            $table->string('accent_light_color', 7)->nullable();
            $table->string('accent_dark_color', 7)->nullable();
            $table->timestamps();
        });
    }
};
