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
    Schema::create('menu_items', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Το όνομα του link, π.χ., "About Us"
        $table->string('link'); // Το URL, π.χ., "/about-us"
        $table->integer('order')->default(0); // Η σειρά εμφάνισης
        $table->unsignedBigInteger('parent_id')->nullable(); // Για μελλοντικά sub-menus
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
