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
            // We need to drop the column and recreate it as json or text because changing to json directly might not be supported on all DBs without doctrine/dbal
            // But for now let's try changing it. If it fails we might need a raw statement or drop/add.
            // Safest way for existing data is to change it to TEXT first (which can hold JSON) or JSON if supported.
            // Let's assume MySQL/Postgres.
            $table->text('receipt_path')->nullable()->change(); 
            // Or better, rename it to receipt_paths and make it json
            // But to keep it simple, let's just change it to TEXT which is flexible enough for JSON.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('receipt_path')->nullable()->change();
        });
    }
};
