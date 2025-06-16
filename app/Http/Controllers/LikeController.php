<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    public function likePost(Request $request)
    {
        $request->validate([
            "post_id" => "required|integer",
        ]);


        try {
            $userLikedPostbefore = Like::where('user_id', auth()->user()->id)
                ->where('post_id', $request->post_id)
                ->first();
            if ($userLikedPostbefore) {
                return response()->json([
                    "message" => "user cannot like post twice"
                ]);
            } else {
                $post = new Like();
                $post->post_id = $request->post_id;
                $post->user_id = auth()->user()->id;
                $post->save();
                return response()->json([
                    'message' => 'Post liked successfully',
                    // 'post_data' => $post
                ], 200);
            }
        } catch (\Exception $exception) {

            return response()->json(['error' => $exception->getMessage()], 403);
        }
    }
}
