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
        Schema::table('estimates', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('status');
            $table->decimal('tax', 10, 2)->default(0)->after('subtotal');
            $table->decimal('discount', 10, 2)->default(0)->after('tax');
            $table->text('notes')->nullable()->after('total_amount');
            $table->text('terms')->nullable()->after('notes');
        });

        Schema::table('estimate_items', function (Blueprint $table) {
            $table->string('title')->nullable()->after('estimate_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estimate_items', function (Blueprint $table) {
            $table->dropColumn('title');
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'tax', 'discount', 'notes', 'terms']);
        });
    }
};
