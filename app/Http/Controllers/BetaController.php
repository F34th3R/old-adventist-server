<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Controllers\Helpers\HeaderHelper;
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
            ], 200, HeaderHelper::$header);
        } else {
            return response()->json([
                "data" => null,
            ], 200, HeaderHelper::$header);
        }
    }

    public function userComment()
    {
        if (Auth::user()->role_id != 1)
        {
            $data = Comment::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            return response()->json([
                "data" => $data,
            ], 200, HeaderHelper::$header);
        } else {
            return response()->json([
                "data" => null,
            ], 200, HeaderHelper::$header);
        }
    }

    public function store(Request $request)
    {
        try {
            Comment::create([
                'comment' => $request->comment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "response" => false,
            ], 404, HeaderHelper::$header);
        }
        return response()->json([
            "response" => true,
        ], 200, HeaderHelper::$header);
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
            ], 200, HeaderHelper::$header);
        }
        return response()->json([
            "data" => true,
        ], 200, HeaderHelper::$header);
    }

    public function deleteComment(Comment $id)
    {
        try {
            $id->delete();
        } catch (\Exception $e) {
            return response()->json([
                "data" => false,
            ], 200, HeaderHelper::$header);
        }
        return response()->json([
            "data" => true,
        ], 200, HeaderHelper::$header);
    }
}
