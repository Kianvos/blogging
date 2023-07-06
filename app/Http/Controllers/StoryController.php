<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function getAllStories(Request $request)
    {
        $host = $request->getSchemeAndHttpHost();
        $stories = Story::leftJoin('posts', 'stories.id', '=', 'posts.story_id')
            ->select('stories.*')
            ->selectRaw('MAX(posts.created_at) as max')
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                }
            ])
            ->groupBy('stories.id')
            ->orderByDesc('max')
            ->get();

        foreach ($stories as $story) {
            $story->image = "$host/image/story/$story->id";
        }
//        error_log($stories);

        return $stories;
    }

    public function getUserStory(Request $request)
    {
        $host = $request->getSchemeAndHttpHost();
        $storyId = $request->route("id");

        $story = Story::with(['posts' => function ($query) {
            $query->orderBy('created_at', 'desc');
            $query->with('images');
        }])->find($storyId);

        if (!$story) {
            return response(["error" => "Story not found"], 404);
        }
        $story->image = "$host/image/story/$story->id";
        foreach ($story->posts as $post) {
            foreach ($post->images as $image) {
                $image->image = "$host/image/$image->id";
            }
        }

        return $story;
    }

    public function createStory(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');

        if ($title === null || $description === null){
            return response(["error" => "Not all inputs given"], 400);
        }

        $userId = $request->user()['id'];
        return Story::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'user_id' => $userId,
            'image' => $request->input('image')
        ]);
    }

    public function editStory(Request $request)
    {
        $userId = $request->user()['id'];
        $storyId = $request->route("id");
        $story = Story::find($storyId);
        //Check of story exists, then check of story is of user
        if (!$story) {
            return response(["error" => "Story not found"], 404);
        } elseif ($story->user_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $title = $request->input('title');
        $description = $request->input('description');
        $image = $request->input('image');
        if ($title === null || $description === null) {
            return response(["error" => "Not all inputs given"], 400);
        }
        if ($image !== "none") {
            $story->update([
                'title' => $title,
                'description' => $description,
                'image' => $image
            ]);
        } else {
            $story->update([
                'title' => $title,
                'description' => $description
            ]);
        }

        return $story;
    }

    public function deleteStory(Request $request)
    {
        $userId = $request->user()['id'];
        $storyId = $request->route("id");
        $story = Story::find($storyId);
        //Check of story exists, then check of story is of user
        if (!$story) {
            return response(["error" => "Story not found"], 404);
        } elseif ($story->user_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if ($story->delete()){
            return response([], 204);
        }
        return response(["Error deleting story, try again later"], 500);
    }
}
