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
        Schema::create('agentcommisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained();
            $table->foreignId('boxtype_id')->constrained();
            $table->foreignId('zone_id')->nullable()->constrained();
            $table->decimal('commision_amount', 10, 2)->unsigned();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agentcommisions');
    }
};
