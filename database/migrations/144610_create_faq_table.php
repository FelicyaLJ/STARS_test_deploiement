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
        Schema::create('faq', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 255);
            $table->text('texte');
            $table->string('fichier', 255)->nullable();
            $table->integer('ordre_affichage')->index();
            $table->timestamps();
            $table->unsignedBigInteger('id_categorie');
        });
        Schema::table('faq', function (Blueprint $table) {
            $table->foreign('id_categorie')->references('id')->on('categorie_faq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq');
    }
};
