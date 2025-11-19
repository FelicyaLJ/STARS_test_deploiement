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
        Schema::create('users_role', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_role');

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_role')->references('id')->on('role')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_role');
    }
};
