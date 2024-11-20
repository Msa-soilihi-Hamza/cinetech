<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('media_type'); // Pour distinguer entre 'movie' et 'series'
            $table->unsignedBigInteger('media_id'); // ID du film ou de la série depuis l'API
            $table->unsignedBigInteger('parent_id')->nullable(); // Pour les réponses aux commentaires
            
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index(['media_type', 'media_id']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
