<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('target', 10, 2)->default(100);
            $table->decimal('achieved', 10, 2)->default(0);
            $table->decimal('score', 5, 2)->default(0);
            $table->string('metric_unit')->default('%');
            $table->year('year');
            $table->tinyInteger('month');
            $table->enum('period', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'approved'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kpis');
    }
};
