<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Drop the existing unique index on 'key'
            $table->dropUnique(['key']);
            
            // Add a composite unique index on 'key' and 'branch_id'
            $table->unique(['key', 'branch_id']);
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['key', 'branch_id']);
            $table->unique('key');
        });
    }
};
