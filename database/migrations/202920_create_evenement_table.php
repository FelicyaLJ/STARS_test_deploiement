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
        Schema::create('evenement', function (Blueprint $table) {
            $table->id();
            $table->string('nom_evenement', 255);
            $table->string('description', 255)->nullable();
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->decimal('prix', 10, 2)->nullable();
            $table->unsignedBigInteger('id_categorie');
            $table->unsignedBigInteger('id_etat');
            $table->unsignedBigInteger('id_terrain')->nullable();

            $table->index('date');
            $table->index(['date', 'id_categorie']);
        });
        Schema::table('evenement', function (Blueprint $table) {
            $table->foreign('id_categorie')->references('id')->on('categorie_evenement');
            $table->foreign('id_etat')->references('id')->on('etat_evenement');
            $table->foreign('id_terrain')->references('id')->on('terrain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenement');
    }
};
