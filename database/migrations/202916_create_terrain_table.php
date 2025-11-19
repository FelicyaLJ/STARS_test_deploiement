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
        Schema::create('terrain', function (Blueprint $table) {
            $table->id();
            $table->string('nom_terrain', 80);
            $table->string('description', 255)->nullable();
            $table->decimal('latitude', 9, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();
            $table->string('adresse', 255);
            $table->tinyInteger('visible');
            $table->string('couleur', 7);
            $table->unsignedBigInteger('id_parent')->nullable();
        });
        Schema::table('terrain', function (Blueprint $table) {
            $table->foreign('id_parent')->references('id')->on('terrain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terrain');
    }
};
