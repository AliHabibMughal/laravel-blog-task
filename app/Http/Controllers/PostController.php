<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\{Post, Category, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'status' => true,
            'message' => 'All Posts With Users Retrieved Successfully',
            'data' => PostResource::collection($posts),
            // 'data' => $posts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatePost = Validator::make(
            $request->all(),
            [
                'title' => 'required|max:100|string|unique:posts,title',
                'body' => 'required',
                'category_id' => 'required',
            ],
        );
        if ($validatePost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Blog fields are required',
                'errors' => $validatePost->errors()
            ], 401);
        }

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'slug' =>  Str::slug($request->title),
            'user_id' => Auth::id(),
        ]);

        $post->categories()->sync($request->category_id);
        return response()->json([
            'status' => true,
            'message' => 'Post Created Successfully',
            'data' => $post,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post Not Found',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Post with user retrieved successfully',
            'data' => new PostResource($post),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return response()->json([
                'status' => false,
                'message' => 'Post Not Found',
            ]);
        }
        $validatePost = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'body' => 'required',
            ],
        );
        if ($validatePost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Enter Post Title & Body',
                'errors' => $validatePost->errors()
            ], 401);
        }

        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();
        return response()->json([
            'status' => true,
            'message' => 'Category Updated Successfully',
            'data' => $post,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return response()->json([
                'status' => false,
                'message' => 'Post Not Found',
            ]);
        }
        $post->delete();
        return response()->json([
            'status' => true,
            'message' => 'Post Deleted Successfully',
            'data' => $post,
        ]);
    }
}
