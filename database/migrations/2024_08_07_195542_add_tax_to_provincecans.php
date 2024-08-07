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
        Schema::table('provincecans', function (Blueprint $table) {
            $table->decimal('gst', 10, 2)->nullable()->unsigned()->after('name');
            $table->decimal('pst', 10, 2)->nullable()->unsigned()->after('gst');
            $table->decimal('hst', 10, 2)->nullable()->unsigned()->after('pst');

            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provincecans', function (Blueprint $table) {
            $table->dropColumn('gst');
            $table->dropColumn('pst');
            $table->dropColumn('hst');
        });
    }
};
