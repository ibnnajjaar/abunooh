<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 400);
            $table->string('slug', 400)->unique();
            $table->text('excerpt')->nullable();
            $table->text('content')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->string('status')->comment(\App\Support\Enums\PublishStatuses::class);
            $table->string('post_type')->comment(\App\Support\Enums\PostTypes::class);
            $table->boolean('is_menu_item')->default(false);
            $table->string('og_image_url')->nullable();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
