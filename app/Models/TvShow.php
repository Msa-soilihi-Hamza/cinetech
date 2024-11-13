<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvShow extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'overview',
        'poster_path',
        'backdrop_path',
        'first_air_date',
        'vote_average',
        'tmdb_id'
    ];

    protected $casts = [
        'first_air_date' => 'date',
        'vote_average' => 'float'
    ];
}
