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
        Schema::create('equipe_evenement', function (Blueprint $table) {
            $table->unsignedBigInteger('id_equipe');
            $table->unsignedBigInteger('id_evenement');

            $table->foreign('id_equipe')->references('id')->on('equipe')->onDelete('cascade');
            $table->foreign('id_evenement')->references('id')->on('evenement')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipe_evenement');
    }
};
