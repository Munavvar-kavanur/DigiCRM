<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get Main Branch ID
        $mainBranchId = DB::table('branches')->where('name', 'Main Branch')->value('id');

        $tables = [
            'users',
            'clients',
            'projects',
            'invoices',
            'estimates',
            'expenses',
            'payrolls',
            'tasks',
            'reminders',
            'settings'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'branch_id')) {
                        $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->onDelete('cascade');
                    }
                });

                // Assign existing records to Main Branch
                if ($mainBranchId) {
                    DB::table($tableName)->update(['branch_id' => $mainBranchId]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'clients',
            'projects',
            'invoices',
            'estimates',
            'expenses',
            'payrolls',
            'tasks',
            'reminders',
            'settings'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'branch_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['branch_id']);
                    $table->dropColumn('branch_id');
                });
            }
        }
    }
};
