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
        Schema::create('equipe', function (Blueprint $table) {
            $table->id();
            $table->string('nom_equipe', 60);
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 2)->nullable();
            $table->smallInteger('ordre_affichage')->index();
            $table->unsignedBigInteger('id_categorie');
            $table->unsignedBigInteger('id_genre');
            $table->unsignedBigInteger('id_etat');
        });
        Schema::table('equipe', function (Blueprint $table) {
            $table->foreign('id_categorie')->references('id')->on('categorie_equipe');
            $table->foreign('id_genre')->references('id')->on('genre_equipe');
            $table->foreign('id_etat')->references('id')->on('etat_equipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipe');
    }
};
