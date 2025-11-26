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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('reminder_date');
            $table->enum('type', ['invoice', 'project', 'estimate', 'payroll', 'custom'])->default('custom');
            $table->nullableMorphs('related'); // Adds related_id and related_type
            $table->enum('status', ['pending', 'completed', 'dismissed'])->default('pending');
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->boolean('is_recurring')->default(false);
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
