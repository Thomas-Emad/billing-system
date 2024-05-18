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
        Schema::create('indebtednesses', function (Blueprint $table) {
            $table->id();
            $table->decimal('debtor');
            $table->foreignId('customer_id')->constrained('customers', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('invoice_id')->constrained('invoices', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indebtednesses');
    }
};
