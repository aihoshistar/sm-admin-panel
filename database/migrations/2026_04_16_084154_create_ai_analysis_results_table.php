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
        Schema::create('ai_analysis_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('artists')->onDelete('cascade');
            $table->string('source_platform')->comment('youtube, x, community');
            $table->string('analysis_type')->comment('summary, sentiment, trend');
            $table->longText('result_text')->comment('Gemini AI 분석 결과');
            $table->json('meta_data')->nullable()->comment('긍정/부정 점수 등 부가 정보');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_analysis_results');
    }
};
