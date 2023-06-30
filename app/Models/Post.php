<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Testing\Fluent\Concerns\Has;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'story_id',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public static function userIsOwnerPost($userId, $postId){
        $post = Post::find($postId);
        if ($post){

            $story = Story::find($post->story_id);
            if ($story){
                if ($story->user_id === $userId){
                    return true;
                }
            }
        }
        return false;
    }
}
