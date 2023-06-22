<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserStories(Request $request)
    {
        $userId = $request->user()['id'];
        $user = User::find($userId);
        return $user->stories;
    }
}
