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
        Schema::table('branches', function (Blueprint $table) {
            if (!Schema::hasColumn('branches', 'code')) {
                $table->string('code')->nullable()->unique()->after('name');
            }
        });

        // Update existing branches with a default code
        $branches = \App\Models\Branch::all();
        foreach ($branches as $branch) {
            if (empty($branch->code)) {
                // Generate a code based on name or random
                $code = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $branch->name), 0, 3));
                // Ensure uniqueness (simple check)
                if (\App\Models\Branch::where('code', $code)->exists()) {
                    $code .= rand(10, 99);
                }
                $branch->code = $code;
                $branch->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (Schema::hasColumn('branches', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};
