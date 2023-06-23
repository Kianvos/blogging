<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function createPost(Request $request, Image $image, User $user)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $images = $request->input('images');
        $userId = $request->user()['id'];
        $storyId = $request->route("storyId");

        if ($title === null || $description === null) {
            return response(["error" => "Not all inputs given"], 400);
        } elseif (!$user->isUserAuthorizedByStory($userId, $storyId)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $newPost = Post::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'story_id' => $storyId
        ]);
        if ($images !== null) {
            $image->addImagesToPost($newPost->id, $images);
        }
        return response([], 204);
    }

    public function editPost(Request $request, User $user, Image $image)
    {
        $postId = $request->route("id");
        $userId = $request->user()['id'];
        $title = $request->input('title');
        $images = $request->input('images');

        $post = Post::find($postId);

        $description = $request->input('description');
        if (!$post) {
            return response(["error" => "Post not found"], 404);
        } elseif ($title === null || $description === null) {
            return response(["error" => "Not all inputs given"], 400);
        } elseif (!$user->isUserAuthorizedByStory($userId, $post->story_id)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $post->update([
            'title' => $title,
            'description' => $description,
        ]);

        $image->imagesUpdate($postId, $images);
        $images = $post->images;
        $post->images = $images;

        return $post;
    }

    public function deletePost(Request $request, User $user)
    {
        $userId = $request->user()['id'];
        $postId = $request->route("id");

        $post = Post::find($postId);
        //Check of story exists, then check of story is of user
        if (!$post) {
            return response(["error" => "Post not found"], 404);
        } elseif (!$user->isUserAuthorizedByStory($userId, $post->story_id)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if ($post->delete()) {
            return response([], 204);
        }
        return response(["Error deleting post, try again later"], 500);
    }
}
