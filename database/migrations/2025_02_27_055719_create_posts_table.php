<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('original_user_id')->nullable()->constrained('users')->nullOnDelete();
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
            $table->string('post_template')->default('post');
            $table->json('list_data_json')->nullable();
            $table->text('list_data_html')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('channel_id')->nullable()->constrained('channels')->nullOnDelete();
            $table->softDeletes();
            // vistas
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
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
