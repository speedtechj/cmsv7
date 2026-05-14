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
        Schema::table('invoicestatuses', function (Blueprint $table) {
            $table->index(['generated_invoice', 'manual_invoice']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoicestatuses', function (Blueprint $table) {
            $table->dropIndex(['generated_invoice', 'manual_invoice']);
        });
    }
};
