<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_post = Post::get();

        return response()->json([
            $all_post
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {

        try {
            $post = Auth::user()->posts()->create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'body' => $request->body,
            ]);

            return response()->json([
                $post,
                'message' => 'Post Created Sucess'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => 'Post creation failed'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('user')->where('id', $id)->first();

        if (!$post) {
            return response()->json([
                'message' => "Page Content Not Found"
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            $post
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Post $post)
    {

        try {
            //

            if ($request->user()->id !== $post->user_id) {
                return response()->json(['message' => 'Unauthorized. You do not own this post.'], Response::HTTP_FORBIDDEN);
            }

            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'content' => 'sometimes|required|string',
            ]);


            return response()->json([$post, 'message' => "Sucessfully updated post"], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => 'Search failed.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post)
    {
        try {
            if ($request->user()->id !== $post->user_id) {
                return response()->json(['message' => 'Unauthorized Action . You do not own this post'], Response::HTTP_FORBIDDEN);
            }

            $post->delete();

            return response()->json(['message' => 'Post deleted successfully'], Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return response()->json(['error' => 'post deletion failed.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function search(Request $request)
    {
        try {

            $query = $request->input('query');

            $posts = Post::with('user')
                ->where('title', 'like', "%{$query}%")
                ->orWhere('body', 'like', "%{$query}%")
                ->get();

            return response()->json($posts);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Search failed.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
