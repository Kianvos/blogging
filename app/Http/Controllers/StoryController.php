<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function getAllStories()
    {
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






        return $stories;
    }

    public function getUserStory(Request $request)
    {
        $storyId = $request->route("id");

        $story = Story::with(['posts' => function ($query) {
            $query->orderBy('created_at', 'desc');
            $query->with('images');
        }])->find($storyId);

        if (!$story) {
            return response(["error" => "Story not found"], 404);
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
        if ($title === null || $description === null){
            return response(["error" => "Not all inputs given"], 400);
        }
        $story->update([
            'title' => $title,
            'description' => $description,
            'image' => $request->input('image')
        ]);
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
