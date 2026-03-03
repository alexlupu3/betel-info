<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_item_locations', function (Blueprint $table) {
            $table->foreignId('content_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->primary(['content_item_id', 'location_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_item_locations');
    }
};
