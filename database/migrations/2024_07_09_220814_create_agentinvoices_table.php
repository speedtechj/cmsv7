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
        Schema::create('agentinvoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained();
            $table->bigInteger('manual_invoice');
            $table->Date('date_issued');
            $table->boolean('is_used')->default(false);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agentinvoices');
    }
};
