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

    public function addImagesToPost($postId, $images)
    {
        foreach ($images as $image) {
            Image::create([
                'description' => $image['description'],
                'image' => $image['image'],
                'post_id' => $postId
            ]);
        }
    }

    public function imagesUpdate($postId, $images)
    {

        //Bekijken welke id er in staan voor verwijderen
        if (isset($images['delete'])){
            foreach ($images['delete'] as $image) {
                $imageDB = Image::find($image);
                if ($imageDB){
                    if ($imageDB->post_id == $postId){
                        $imageDB->delete();

                    }
                }
            }
        }

        if (isset($images['new'])){
            foreach ($images['new'] as $image) {
                Image::create([
                    'description' => $image['description'],
                    'image' => $image['image'],
                    'post_id' => $postId
                ]);
            }
        }
    }
}
