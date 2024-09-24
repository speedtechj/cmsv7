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
        Schema::create('calllogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calltype_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('sender_id')->constrained();
            $table->date('calldate');
            $table->text('callnotes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calllogs');
    }
};
