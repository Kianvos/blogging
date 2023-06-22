<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'post_id',
        'image'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
