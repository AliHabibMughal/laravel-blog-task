<?php

namespace App\Http\Controllers;

use App\Models\{Like, Post, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $likes = Post::withCount('likes')->get();
        // $likes = Like::count();
        return response()->json([
            'status' => true,
            'message' => 'Posts With Total Likes Retrieved Successfully',
            'data' => $likes,
            // 'data' => LikeResource::collection($likes),
        ]);
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
        $validateLike = Validator::make(
            $request->all(),
            [
                // 'postlikes' => 'required',
                'post_id' => 'required',
            ],
        );
        if ($validateLike->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Post Required',
                'errors' => $validateLike->errors()
            ], 401);
        }
        // $DBUserId = DB::table('likes')->pluck('user_id');

        $likeFields = [
            ['user_id', Auth::id()],
            ['post_id', $request->post_id],
        ];

        $like = Like::where($likeFields);

        if ($like->exists()) {
            $like->delete();
            return response()->json([
                'status' => true,
                'message' => 'Disliked Successfully',

            ], 200);

        } else {
            $liked = Like::create([
                'user_id' => Auth::id(),
                'post_id' => $request->post_id,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Liked Successfully',
                'data' => $liked,

            ], 200);
        }

        // if ( !(Like::where('user_id', Auth::id())->where('post_id', $request->post_id)->first()) ) {

        //     $like = Like::create([
        //         // 'postlikes' => $request->postlikes,
        //         'user_id' => Auth::id(),
        //         'post_id' => $request->post_id,
        //     ]);

        //     // $user->likes()->create([])
        //     return response()->json([
        //         'status' => true,
        //         'message' => 'Post Liked Successfully',
        //         'data' => $like,
        //     ], 200);

        // } else {
        //     return response()->json([
        //         'status' => true,
        //         'message' => 'This User Has Already Liked This Post',
        //     ]);
        // }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $like = Like::find($id);
        if (!$like) {
            return response()->json([
                'status' => false,
                'message' => 'This Like is Not Found',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Like Retrieved Successfully',
            'data' => $like,
        ]);
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
