<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BetaController extends Controller
{
    public function indexComments()
    {
        if (Auth::user()->role_id == 1)
        {
            $data = Comment::orderBy('id', 'DESC')->get();
            return response()->json([
                "data" => $data,
            ], 200);
        } else {
            return response()->json([
                "data" => null,
            ], 200);
        }
    }

    public function userComment()
    {
        if (Auth::user()->role_id != 1)
        {
            $data = Comment::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            return response()->json([
                "data" => $data,
            ], 200);
        } else {
            return response()->json([
                "data" => null,
            ], 200);
        }
    }

    public function createComment(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);
        try {
            Comment::create([
                'comment' => $request->comment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "data" => false,
            ], 200);
        }
        return response()->json([
            "data" => true,
        ], 200);
    }

    public function updateComment(Request $request, Comment $id)
    {
        try {
            $id->update([
                'isComplete' => $request->complete
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "data" => false,
            ], 200);
        }
        return response()->json([
            "data" => true,
        ], 200);
    }

    public function deleteComment(Comment $id)
    {
        try {
            $id->delete();
        } catch (\Exception $e) {
            return response()->json([
                "data" => false,
            ], 200);
        }
        return response()->json([
            "data" => true,
        ], 200);
    }
}
