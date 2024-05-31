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
        Schema::create('shippingbookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shippingagent_id');
            $table->date('booking_date');
            $table->string('booking_no');
            $table->foreignId('carrier_id');
            $table->string('vessel');
            $table->string('return_terminal');
            $table->string('origin_terminal');
            $table->string('port_of_loading');
            $table->string('port_of_unloading');
            $table->date('etd');
            $table->date('eta');
            $table->string('bill_of_lading')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('branch_id');
            $table->string('commodity');
            $table->string('hs_code');
            $table->string('place_of_receipt');
            $table->foreignId('user_id');
            $table->boolean('is_complete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippingbookings');
    }
};
