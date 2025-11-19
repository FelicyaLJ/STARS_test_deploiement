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
        Schema::create('alerte', function (Blueprint $table) {
            $table->id();
            $table->timestamp('timestamp_fin');
            $table->unsignedBigInteger('id_evenement');
        });
        Schema::table('alerte', function (Blueprint $table) {
            $table->foreign('id_evenement')->references('id')->on('evenement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerte');
    }
};
