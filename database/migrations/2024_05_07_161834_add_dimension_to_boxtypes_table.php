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
            $table->decimal('lenght')->after('dimension')->nullable();
            $table->decimal('width')->after('lenght')->nullable();
            $table->decimal('height')->after('width')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boxtypes', function (Blueprint $table) {
            $table->dropColumn('lenght');
            $table->dropColumn('width');
            $table->dropColumn('height');
        });
    }
};
