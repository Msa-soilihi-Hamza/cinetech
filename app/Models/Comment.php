<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'content',
        'user_id',
        'media_type',
        'media_id',
        'parent_id',
       
    ];

    // Relation avec l'utilisateur
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec les réponses
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // Relation avec le commentaire parent
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
