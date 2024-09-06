<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('image');
            $table->string('excerpt');
            $table->longText('body');
            $table->foreignUuid('category_id');
            $table->foreignUuid('author_id');
            $table->foreignUuid('published_by')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
