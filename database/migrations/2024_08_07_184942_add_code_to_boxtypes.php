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
        Schema::table('boxtypes', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id');
            $table->decimal('price', 10, 2)->nullable()->unsigned()->after('code');
            $table->decimal('delivery_charge', 10, 2)->unsigned()->after('price');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boxtypes', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('price');
            $table->dropColumn('delivery_charge');
        });
    }
};
