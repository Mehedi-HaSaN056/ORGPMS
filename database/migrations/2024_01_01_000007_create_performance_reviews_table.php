<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewed_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained();
            $table->year('year');
            $table->tinyInteger('month');
            $table->decimal('task_completion_score', 5, 2)->default(0);
            $table->decimal('kpi_score', 5, 2)->default(0);
            $table->decimal('communication_score', 5, 2)->default(0);
            $table->decimal('punctuality_score', 5, 2)->default(0);
            $table->decimal('quality_score', 5, 2)->default(0);
            $table->decimal('overall_score', 5, 2)->default(0);
            $table->enum('rating', ['excellent', 'good', 'average', 'below_average', 'poor'])->nullable();
            $table->text('strengths')->nullable();
            $table->text('improvements')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('performance_reviews');
    }
};
