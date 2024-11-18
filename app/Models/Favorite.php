<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id', // lid de l'utilisateur qui ajouter en favoris
        'tmdb_id', // id de la série ou du film
        'type' // type de média (movie ou tv)
    ];

    protected $casts = [
        'tmdb_id' => 'integer', // id de la série ou du film
        'type' => 'string' // type de média (movie ou tv)
    ];
    // pour éviter les erreurs de type

    public function user()
    {
        return $this->belongsTo(User::class); // cette fonction permet de récupérer l'utilisateur d'etre en lien avec
        // ses favoris
    }
}


// En resume se medele permet de definir ce que c'est les favoris  dans sur notre site 
//s'assurrer que les donne sont de bon type 
// et de faire le lien avec l'utilisateur qui a ajouter en favoris
