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
        Schema::create('publication', function (Blueprint $table) {
            $table->id();
            $table->text('texte');
            $table->string('titre')->nullable();
            $table->string('fichier', 500)->nullable();
            $table->tinyInteger('from_facebook');
            $table->timestamps();
            $table->unsignedBigInteger('id_etat')->unsigned();
        });
        Schema::table('publication', function (Blueprint $table) {
            $table->foreign('id_etat')->references('id')->on('etat_publication');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publication');
    }
};
