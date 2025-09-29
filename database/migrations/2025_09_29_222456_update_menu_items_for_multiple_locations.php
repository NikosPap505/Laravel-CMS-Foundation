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
    Schema::table('menu_items', function (Blueprint $table) {
        $table->dropColumn('location'); // Αφαιρούμε την παλιά στήλη
        $table->boolean('show_in_header')->default(true)->after('link');
        $table->boolean('show_in_footer')->default(false)->after('show_in_header');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            //
        });
    }
};
