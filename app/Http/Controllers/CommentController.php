<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comment = Comment::all();
        return response()->json([
            'status' => true,
            'message' => 'All Comments Retrieved Successfully',
            'data' => $comment,
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
                'body' => 'required',
            ],
        );
        if ($validatePost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Empty Comment Field!',
                'errors' => $validatePost->errors()
            ], 401);
        }

        $comment = Comment::create([
            'body' => $request->body,
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment Posted Successfully',
            'data' => $comment,
        ], 200);
    }

    public function replyStore(Request $request)
    {
        $reply = Comment::create([
            'body' => $request->body,
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
            'parent_id' => $request->parent_id,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Reply Posted Successfully',
            'data' => $reply,
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
        $comment = Comment::find($id);
        if (is_null($comment)) {
            return response()->json([
                'status' => false,
                'message' => 'Comment Not Found',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Comment Retrieved Successfully',
            'data' => $comment,
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
        $comment = Comment::find($id);
        if (is_null($comment)) {
            return response()->json([
                'status' => false,
                'message' => 'Comment Not Found',
            ]);
        }

        $validateComment = Validator::make(
            $request->all(),
            [
                'body' => 'required',
            ],
        );
        if ($validateComment->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Enter Comment',
                'errors' => $validateComment->errors()
            ], 401);
        }
        $comment->body = $request->body;
        $comment->save();
        return response()->json([
            'status' => true,
            'message' => 'Comment Updated Successfully',
            'data' => $comment,
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
        $comment = Comment::find($id);
        if (is_null($comment)) {
            return response()->json([
                'status' => false,
                'message' => 'Comment Not Found',
            ]);
        }
        
        $comment->delete();
        return response()->json([
            'status' => true,
            'message' => 'Comment Deleted Successfully with all replies(if any)',
            'data' => $comment,
        ]);
    }
}
