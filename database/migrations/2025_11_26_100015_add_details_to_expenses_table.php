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
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('receipt_path')->nullable()->after('amount');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('receipt_path');
            $table->string('merchant')->nullable()->after('description');
            $table->string('reference')->nullable()->after('merchant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['receipt_path', 'status', 'merchant', 'reference']);
        });
    }
};
