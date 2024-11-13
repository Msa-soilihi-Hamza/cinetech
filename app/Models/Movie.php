<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'overview',
        'poster_path',
        'backdrop_path',
        'release_date',
        'vote_average',
        'tmdb_id'
    ];

    protected $casts = [
        'release_date' => 'date',
        'vote_average' => 'float'
    ];
}
