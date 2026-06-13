<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->constrained();
            $table->text('description');
            $table->decimal('hours_spent', 5, 2)->nullable();
            $table->date('log_date');
            $table->enum('status', ['draft', 'submitted'])->default('submitted');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('work_logs');
    }
};
