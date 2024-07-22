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
        Schema::create('purchaseitems', function (Blueprint $table) {
            $table->id();
            $table->date('order_date');
            $table->foreignId('posinvoice_id')->constrained('posinvoices')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('sender_id')->constrained();
            $table->foreignId('senderaddress_id')->constrained();
            $table->foreignId('boxtype_id')->constrained();
            $table->bigInteger('quantity');
            $table->bigInteger('discount_amount')->unsigned()->nullable();
            $table->decimal('total_amount', 10, 2)->unsigned();
            $table->string('status')->default('pending');
            $table->boolean('is_paid')->default(false);
            $table->date('delivery_date')->nullable();
            $table->foreignId('agent_id')->constrained()->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchaseitems');
    }
};
