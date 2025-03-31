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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('extract')->nullable();
            $table->text('body');
            $table->string('thumbnail')->nullable();
            $table->string('language')->nullable();
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('status')->default('draft');
            $table->boolean('featured')->default(false);
            $table->foreignId('category_id')->constrained('categories');
            // visitas
            // likes
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
