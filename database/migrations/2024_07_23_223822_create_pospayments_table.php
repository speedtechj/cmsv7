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
        Schema::create('pospayments', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->nullable();
            $table->foreignId('sender_id')->constrained();
            $table->foreignId('posinvoice_id')->constrained('posinvoices')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('paymenttype_id')->constrained();
            $table->date('payment_date');
            $table->decimal('payment_amount', 10, 2)->unsigned();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pospayments');
    }
};
