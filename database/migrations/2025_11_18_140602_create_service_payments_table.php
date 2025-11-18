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
        Schema::create('service_payments', function (Blueprint $table) {
            $table->id();
            $table->enum('service_type', ['bin', 'cleaning']);
            $table->date('date_paid'); // When the payment was made
            $table->date('incurred_for_month'); // **CRITICAL FOR BILLING**: The month this fee should be allocated to (e.g., '2025-03-01')
            $table->decimal('amount_paid', 8, 2); // Amount paid to the contractor
            $table->decimal('total_fee_due', 8, 2); // Total amount to be collected from tenants
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_payments');
    }
};
