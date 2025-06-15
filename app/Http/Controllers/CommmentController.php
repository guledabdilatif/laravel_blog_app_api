<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommmentController extends Controller
{
    public function postComment(Request $request){
         $request->validate([
            "post_id" => "required|integer",
            "comment" => "required|string",
        ]);

         try {
            $post = new Comment();
            $post->post_id = $request->post_id;
            $post->comment = $request->comment;
            $post->user_id = auth()->user()->id;
            $post->save();
            return response()->json([
                'message' => 'comment added successfully',
                // 'post_data' => $post
            ], 200);
        } catch (\Exception $exception) {

            return response()->json(['error' => $exception->getMessage()], 403);
        }
    }
}
