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
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('post_series_id')->nullable()
                ->constrained('post_series')->nullOnDelete()->after('views');
            $table->unsignedInteger('series_order')->nullable()->after('post_series_id');
            $table->unique(['post_series_id', 'series_order'], 'post_series_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['post_series_id']);
            $table->dropColumn('post_series_id');
        });
    }
};
