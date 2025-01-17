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
            $table->longText('bl_attachments')->nullable()->after('is_complete');
            $table->string('telex_attachments')->nullable()->after('bl_attachments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shippingbookings', function (Blueprint $table) {
            $table->dropColumn('bl_attachments');
            $table->dropColumn('telex_attachments');
        });
    }
};
