<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Ajouter les nouvelles colonnes
            $table->string('commentable_type')->nullable()->after('content');
            $table->unsignedBigInteger('commentable_id')->nullable()->after('commentable_type');
        });

        // Mettre à jour les données existantes
        DB::statement('UPDATE comments SET commentable_type = CASE 
            WHEN media_type = "movie" THEN "App\\\\Models\\\\Movie" 
            ELSE "App\\\\Models\\\\TvShow" 
            END');
        DB::statement('UPDATE comments SET commentable_id = media_id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['commentable_type', 'commentable_id']);
        });
    }
};
