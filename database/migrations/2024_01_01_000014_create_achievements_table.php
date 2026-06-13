<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('badge_icon')->default('bi-award');
            $table->string('badge_color')->default('#ffc107');
            $table->enum('type', ['task', 'kpi', 'attendance', 'communication', 'special'])->default('special');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('achievements');
    }
};
