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
        Schema::create('system_performance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('service_name')->comment('sm-ai-backend, sm-data-pipeline');
            $table->string('task_name')->comment('endpoint 명 또는 DAG 명');
            $table->float('duration')->comment('소요 시간 (초)');
            $table->boolean('alert_status')->default(false)->comment('Discord 알림 발송 여부');
            $table->timestamp('created_at')->useCurrent();

            $table->index('duration', 'idx_perf_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_performance_logs');
    }
};
