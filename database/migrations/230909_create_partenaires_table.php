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
        Schema::create('partenaires', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 255);
            $table->string('image', 255)->nullable();
            $table->string('lien', 255)->nullable();
            $table->integer('ordre_affichage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partenaires');
    }
};
