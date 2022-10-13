<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
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
        return Post::with('user')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                'title' => 'required|min:5|max:255|string|unique:posts,title',
                'body' => 'required',
            ],
        );
        if ($validatePost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Blog title and body required',
                'errors' => $validatePost->errors()
            ], 401);
        }

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'slug' =>  Str::slug($request->title),
            'user_id' => Auth::id(),
        ]);
        // $post->categories()->attach([]);
        return response()->json([
            'status' => true,
            'message' => 'Post Created Successfully',
            'data' => $post,
        ], 200);

        // $post = new Post();
        // $post->user_id = auth()->user()->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
