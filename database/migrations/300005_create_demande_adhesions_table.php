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
        Schema::create('demande_adhesions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_forum');
            $table->text('raison');
        });

        Schema::table('demande_adhesions', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_forum')->references('id')->on('forum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_adhesions');
    }
};
