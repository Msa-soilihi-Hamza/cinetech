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
    public function up() // cette fonction permet de creer la table favorites
    {
        Schema::create('favorites', function (Blueprint $table) { // cette fonction permet de creer la table favorites
            $table->id(); // cette fonction permet de creer la clé primaire id
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // cette fonction permet de creer la clé étrangère user_id qui est lié à la table users et qui est lié à la table favorites
            $table->unsignedBigInteger('tmdb_id'); // cette fonction permet de creer la clé étrangère tmdb_id qui est lié à la table favorites
            $table->enum('type', ['movie', 'tv']); // cette fonction permet de creer la clé étrangère type qui est lié à la table favorites
            $table->timestamps(); // cette fonction permet de creer les colonnes created_at et updated_at
            
            $table->unique(['user_id', 'tmdb_id', 'type'], 'unique_user_favorite'); // cette fonction permet de creer une contrainte unique sur les colonnes user_id, tmdb_id et type
            $table->index(['user_id', 'type']); // cette fonction permet de creer une contrainte index sur les colonnes user_id et type 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites'); // cette fonction permet de supprimer la table favorites
    }
};
