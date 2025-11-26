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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('employee_type_id')->nullable()->after('joining_date')->constrained()->nullOnDelete();
            $table->foreignId('payroll_type_id')->nullable()->after('employee_type_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['employee_type_id']);
            $table->dropForeign(['payroll_type_id']);
            $table->dropColumn(['employee_type_id', 'payroll_type_id']);
        });
    }
};
