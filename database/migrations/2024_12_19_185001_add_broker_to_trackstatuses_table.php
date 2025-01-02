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
        Schema::table('trackstatuses', function (Blueprint $table) {
            $table->boolean('is_broker')->default(0)->after('branch_id');
            $table->boolean('is_edit')->default(0)->after('is_broker');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trackstatuses', function (Blueprint $table) {
            $table->dropColumn('is_broker');
            $table->dropColumn('is_edit');
        });
    }
};
