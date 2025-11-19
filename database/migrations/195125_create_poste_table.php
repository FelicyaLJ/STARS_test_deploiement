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
        Schema::create('poste', function (Blueprint $table) {
            $table->id();
            $table->string('nom_poste', 80);
            $table->string('description', 255)->nullable();
            $table->decimal('salaire', 10, 2)->nullable();
            $table->smallInteger('ordre_affichage')->index();
            $table->unsignedBigInteger('id_etat');
        });
        Schema::table('poste', function (Blueprint $table) {
            $table->foreign('id_etat')->references('id')->on('etat_poste');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poste');
    }
};
