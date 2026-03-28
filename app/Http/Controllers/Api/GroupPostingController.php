<?php

namespace App\Http\Controllers\Api;

use App\Events\GroupPosted;
use App\Http\Controllers\Controller;
use App\Models\GroupPosting;
use Illuminate\Http\Request;

class GroupPostingController extends Controller
{
    public function get()
    {
        $groupPostings = GroupPosting::with(['user' => function ($query) {
            $query->select('id', 'name'); // Select only 'id' and 'name' fields from the user table
        }])->get();

        return response()->success("Get all posts", [
            'groupPostings' => $groupPostings,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $groupPost =  $user->groupPostings()->create([
            'message' => $validatedData['message'],
        ]);

        event(new GroupPosted($groupPost));

        // $groupPostings = GroupPosting::with('user')->latest()->get();

        return response()->success("Post added!", [
            'isSent' => true,
            'groupPost' => $groupPost,
        ]);
    }
}
