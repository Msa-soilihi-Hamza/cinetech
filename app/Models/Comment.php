<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'commentable_type',
        'commentable_id',
        'media_type',
        'media_id',
        'parent_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'parent_id' => 'integer'
    ];

    // Relation avec l'utilisateur
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCommentableType()
    {
        return $this->commentable_type ?? ($this->media_type === 'movie' ? 'App\Models\Movie' : 'App\Models\TVShow');
    }

    // Relation polymorphique avec le contenu commenté
    public function commentable(): MorphTo
    {
        return $this->morphTo();
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
