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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_number')->unique()->nullable()->after('id');
            $table->decimal('subtotal', 10, 2)->default(0)->after('status');
            $table->decimal('tax', 10, 2)->default(0)->after('subtotal');
            $table->decimal('discount', 10, 2)->default(0)->after('tax');
            $table->decimal('grand_total', 10, 2)->default(0)->after('total_amount'); // total_amount might be redundant or used as grand_total
            $table->decimal('balance_due', 10, 2)->default(0)->after('grand_total');
            $table->text('notes')->nullable()->after('balance_due');
            $table->text('terms')->nullable()->after('notes');
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('payment_method'); // bank_transfer, cash, etc.
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'subtotal', 'tax', 'discount', 'grand_total', 'balance_due', 'notes', 'terms']);
        });
    }
};
