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
        Schema::table('shippingcontainers', function (Blueprint $table) {
            $table->foreignId('branch_id')->after('id');
            $table->boolean('is_active')->default(true)->after('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shippingcontainers', function (Blueprint $table) {
            $table->dropColumn('branch_id');
            $table->dropColumn('is_active');
        });
    }
};
