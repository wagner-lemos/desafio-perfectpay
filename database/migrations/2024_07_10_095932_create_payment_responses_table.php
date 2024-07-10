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
        Schema::create('payment_responses', function (Blueprint $table) {
            $table->id();
            $table->string('response_id')->index();
            $table->string('customer');
            $table->float('value');
            $table->float('net_value');
            $table->string('billing_type');
            $table->string('status')->default('pendente');
            $table->date('due_date');
            $table->date('original_due_date');
            $table->string('invoice_url');
            $table->string('invoice_number');
            $table->json('audit_log');
            $table->string('external_reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_responses');
    }
};
