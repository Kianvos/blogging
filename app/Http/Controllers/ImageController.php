<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Story;
use Illuminate\Http\Request;

class ImageController extends Controller
{
   public function hostImage(Request $request, Image $image){
       $imageId = $request->route("id");
       $image = $image->getImage($imageId);
       if (!$image){
           return response("Not found", 404);
       }
       $raw_image_string = base64_decode($image->image);

       return response($raw_image_string)->header('Content-Type', 'image/webp');
   }

   public function hostImageStory(Request $request){
       $storyId = $request->route('id');
       $story =  Story::find($storyId);
       if (!$story){
           return response("Not found", 404);
       }
       $raw_image_string = base64_decode($story->image);
       return response($raw_image_string)->header('Content-Type', 'image/webp');
   }
}
