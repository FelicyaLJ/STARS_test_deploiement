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
        Schema::create('candidature', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_poste');
        });
        Schema::table('candidature', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_poste')->references('id')->on('poste');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidature');
    }
};
