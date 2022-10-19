<?php

namespace App\Http\Controllers;

use App\Models\{Like, Post};
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
        return response()->json([
            'status' => true,
            'message' => 'Likes Retrieved Successfully',
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
                // 'user_id' => 'required|unique:users,id',
            ],
        );
        if ($validateLike->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Post & User Required',
                'errors' => $validateLike->errors()
            ], 401);
        }

        $like = Like::create([
            // 'postlikes' => $request->postlikes,
            'user_id' => Auth::id(),
            // 'user_id' => $request->user_id,
            'post_id' => $request->post_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Liked Successfully',
            'data' => $like,
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
