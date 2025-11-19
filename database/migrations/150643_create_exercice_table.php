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
        Schema::create('exercice', function (Blueprint $table) {
            $table->id();
            $table->string('nom_exercice', 150);
            $table->text('texte')->nullable();
            $table->string('fichier', 255)->nullable();
            $table->string('image', 255);
            $table->string('lien', 255)->nullable();
            $table->integer('ordre_affichage');
            $table->unsignedBigInteger('id_forum');
            $table->unsignedBigInteger('id_categorie');
        });
        Schema::table('exercice', function (Blueprint $table) {
            $table->foreign('id_forum')->references('id')->on('forum')->onDelete('cascade');
            $table->foreign('id_categorie')->references('id')->on('categorie_exercice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercice');
    }
};
