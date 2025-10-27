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
        Schema::table('shippingbookings', function (Blueprint $table) {
            $table->boolean('assign_to')->default(false)->after('is_complete');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shippingbookings', function (Blueprint $table) {
            $table->dropColumn('assign_to');
        });
    }
};
