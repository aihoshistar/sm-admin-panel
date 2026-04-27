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
        Schema::create('sns_community_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('artists')->onDelete('cascade');
            $table->string('platform')->comment('theqoo, instiz 등');
            $table->string('post_title');
            $table->longText('post_content');
            $table->integer('view_count')->default(0);
            $table->timestamp('post_created_at')->nullable();
            $table->timestamp('collected_at');

            $table->index(['artist_id', 'platform'], 'idx_comm_artist_platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sns_community_posts');
    }
};
