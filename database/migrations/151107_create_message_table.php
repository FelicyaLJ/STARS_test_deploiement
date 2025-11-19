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
        Schema::create('message', function (Blueprint $table) {
            $table->id();
            $table->text('texte');
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('id_reponse')->nullable();
            $table->unsignedBigInteger('id_forum');
            $table->unsignedBigInteger('id_user');
        });
        Schema::table('message', function (Blueprint $table) {
            $table->foreign('id_reponse')->references('id')->on('message')->nullOnDelete();
            $table->foreign('id_forum')->references('id')->on('forum')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message');
    }
};
