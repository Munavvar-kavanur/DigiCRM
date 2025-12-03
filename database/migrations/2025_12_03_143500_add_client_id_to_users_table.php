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
            $table->foreignId('client_id')->nullable()->after('branch_id')->constrained('clients')->nullOnDelete();
        });

        // Migrate existing relationships
        // For each client that has a user_id, set that user's client_id to the client's id
        $clients = \Illuminate\Support\Facades\DB::table('clients')->whereNotNull('user_id')->get();
        foreach ($clients as $client) {
            \Illuminate\Support\Facades\DB::table('users')
                ->where('id', $client->user_id)
                ->update(['client_id' => $client->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }
};
