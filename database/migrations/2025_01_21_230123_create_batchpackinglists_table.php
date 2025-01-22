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
        Schema::create('batchpackinglists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained();
            $table->longText('packinglist_attachment');
            $table->foreignId('user_id')->constrained();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batchpackinglists');
    }
};
