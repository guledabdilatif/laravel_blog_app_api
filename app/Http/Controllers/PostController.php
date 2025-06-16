<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function addnewpost(Request $request)
    {
        $request->validate([
            "title" => "required|string",
            "content" => "required|string",
        ]);
        // Post::create();
        try {
            $post = new Post;
            $post->title = $request->title;
            $post->content = $request->content;
            $post->user_id = auth()->user()->id;
            $post->save();
            return response()->json([
                'message' => 'post added successfully',
                // 'post_data' => $post
            ], 200);
        } catch (\Exception $exception) {

            return response()->json(['error' => $exception->getMessage()], 403);
        }
    }
    public function editPost(Request $request)
    {
        $request->validate([
            "title" => "required|string",
            "content" => "required|string",
            "post_id" => "required|integer",
        ]);
        try {
            $post_data = Post::find($request->post_id);
            $updatedPost = $post_data->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
            return response()->json([
                'message' => 'post updated successfully',
                'update_post' => $updatedPost,
                // 'post_data' => $post
            ], 200);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()], 403);
        }
    }
    public function editPost2(Request $request, $post_id)
    {
        $request->validate([
            "title" => "required|string",
            "content" => "required|string",

        ]);
        try {
            $post_data = Post::find($post_id);
            $updatedPost = $post_data->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
            return response()->json([
                'message' => 'post updated successfully',
                'update_post' => $updatedPost,
                // 'post_data' => $post
            ], 200);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()], 403);
        }
    }
    public function getAllPost()
    {
        try {
            $posts = Post::all();
            return response()->json([
                "posts" => $posts
            ]);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()], 403);
        }
    }
    public function getSinglePost(Request $request, $post_id)
    {
        try {
            // $post = Post::find($post_id);
            $post=Post::with('user', 'comment','likes')
            ->withCount('likes')
            ->where('id',$post_id)
            ->first();
            return response()->json([
                "post" => $post
            ]);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()], 403);
        }
    }
    
    public function deletePost(Request $request, $post_id)
    {
        try {
            $post = Post::find($post_id);
            $post->delete();
            return response()->json([
                'message' => "post deleted successfully",
                "posts" => $post
            ]);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()], 403);
        }
    }
}
