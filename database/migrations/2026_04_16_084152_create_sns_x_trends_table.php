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
        Schema::create('sns_x_trends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('artists')->onDelete('cascade');
            $table->string('hashtag');
            $table->integer('tweet_count')->default(0);
            $table->longText('sample_tweets')->nullable()->comment('AI 분석용 샘플 텍스트');
            $table->timestamp('collected_at');

            $table->index(['artist_id', 'collected_at'], 'idx_x_artist_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sns_x_trends');
    }
};
