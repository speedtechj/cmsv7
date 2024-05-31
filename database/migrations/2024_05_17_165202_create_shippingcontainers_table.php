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
        Schema::create('shippingcontainers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trucker_id')->constrained();
            $table->foreignId('shippingbooking_id')->constrained();
            $table->foreignId('batch_id')->constrained();
            $table->string('container_no');
            $table->foreignId('equipment_id')->constrained();
            $table->string('seal_no');
            $table->decimal('tare_weight',10,2);
            $table->decimal('cargo_weight',10,2);
            $table->bigInteger('total_box');
            $table->decimal('total_cbm');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippingcontainers');
    }
};
