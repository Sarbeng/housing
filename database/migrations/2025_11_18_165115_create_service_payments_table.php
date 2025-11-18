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

    // this is just the services and how much they cost and how much should be paid for them so dates aren't relevant
    {
        Schema::create('service_payments', function (Blueprint $table) {
            $table->id();
            $table->enum('service_type', ['bin', 'cleaning']);
            #$table->decimal('amount_monthly'); // amount to be paid to the contractor monthly for the service
            $table->decimal('contractor_monthly_payment', 10, 2);
            $table->decimal('tenant_monthly_payment', 10, 2);
            #$table->string('billing_month'); // or $table->date('billing_month');
            $table->string('description')->nullable();
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
