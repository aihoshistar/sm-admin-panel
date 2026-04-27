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
        Schema::create('sns_youtube_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('artists')->onDelete('cascade');
            $table->string('video_id');
            $table->string('video_title');
            $table->bigInteger('view_count')->default(0);
            $table->bigInteger('like_count')->default(0);
            $table->bigInteger('comment_count')->default(0);
            $table->longText('top_comments')->nullable()->comment('AI 분석용 상위 댓글');
            $table->timestamp('collected_at')->comment('수집 시점');

            // 검색 및 분석 성능을 위한 복합 인덱스
            $table->index(['artist_id', 'collected_at'], 'idx_yt_artist_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sns_youtube_stats');
    }
};
