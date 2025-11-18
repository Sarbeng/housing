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
        Schema::create('tenant_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_payment_id')->nullable()->constrained('service_payments')->onDelete('set null');
            
            $table->enum('service_type', ['bin', 'cleaning']);
            $table->date('billing_month'); // The month this fee applies to (e.g., '2025-03-01')
            $table->decimal('amount_charged', 8, 2); // The fixed fee (GH₵20)
            $table->boolean('is_paid')->default(false);
            
            $table->timestamps();

            // Ensure a tenant is only charged once per service type per month
            $table->unique(['tenant_id', 'service_type', 'billing_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_charges');
    }
};