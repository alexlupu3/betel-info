<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_items', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['card', 'poster', 'richtext', 'group']);
            $table->foreignId('parent_id')->nullable()->constrained('content_items')->nullOnDelete();
            $table->integer('sort_order')->default(0);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('thumbnail_url', 2048)->nullable();
            $table->string('image_url', 2048)->nullable();
            $table->date('date')->nullable();
            $table->string('time', 5)->nullable();
            $table->string('link_url', 2048)->nullable();
            $table->string('link_text')->nullable();
            $table->boolean('published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_items');
    }
};
