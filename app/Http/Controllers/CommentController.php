<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
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
            'message' => 'All Comments With Replies Retrieved Successfully',
            'data' => CommentResource::collection($comment),
            // 'data' => $comment,
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
        $validatePost = Validator::make(
            $request->all(),
            [
                'body' => 'required',
                'parent_id' => 'required',
            ],
        );
        if ($validatePost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Reply field is empty!',
                'errors' => $validatePost->errors()
            ], 401);
        }

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
        $comment = Comment::with('replies')->find($id);
        if (is_null($comment)) {
            return response()->json([
                'status' => false,
                'message' => 'Comment Not Found',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Comment With Replies Retrieved Successfully',
            'data' => new CommentResource($comment),
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateReply(Request $request, $id)
    {
        $reply = Comment::find($id);
        if (is_null($reply)) {
            return response()->json([
                'status' => false,
                'message' => 'Reply Not Found',
            ]);
        }

        $validateReply = Validator::make(
            $request->all(),
            [
                'body' => 'required',
            ],
        );
        if ($validateReply->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Enter Reply',
                'errors' => $validateReply->errors()
            ], 401);
        }

        $reply->body = $request->body;
        $reply->save();
        return response()->json([
            'status' => true,
            'message' => 'Reply Updated Successfully',
            'data' => $reply,
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyReply($id)
    {
        $reply = Comment::find($id);
        if (is_null($reply)) {
            return response()->json([
                'status' => false,
                'message' => 'Reply Not Found',
            ]);
        }

        $reply->delete();
        return response()->json([
            'status' => true,
            'message' => 'Reply Deleted Successfully',
            'data' => $reply,
        ]);
    }
}
