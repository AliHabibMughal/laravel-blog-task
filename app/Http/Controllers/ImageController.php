<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::all();
        return response()->json([
            'status' => true,
            'message' => 'All Images Retrieved Successfully',
            'data' => $images,
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
                'title' => 'required',
                'src' => 'required|image|max:1048|mimes:jpg,png,jpeg,gif,svg',
                'post_id' => 'required',
            ],
        );
        if ($validatePost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Enter image data',
                'errors' => $validatePost->errors()
            ], 401);
        }

        $image = Image::create([
            'title' => $request->title,
            'alt' => $request->alt,
            'src' => $request->file('src')->store('image', 'public'),
            'post_id' => $request->post_id,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Image Uploaded Successfully',
            'data' => $image,
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
        $image = Image::find($id);
        if (is_null($image)) {
            return response()->json([
                'status' => false,
                'message' => 'Image Not Found',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Image Retrieved Successfully',
            'data' => $image,
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
        $image = Image::find($id);
        if (is_null($image)) {
            return response()->json([
                'status' => false,
                'message' => 'image Not Found',
            ]);
        }

        $validateImage = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'src' => 'required|image|max:1048|mimes:jpg,png,jpeg,gif,svg',
                'post_id'=> 'required',
            ],
        );
        if ($validateImage->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Enter Image Data',
                'errors' => $validateImage->errors()
            ], 401);
        }
        $image->body = $request->body;
        $image->save();
        return response()->json([
            'status' => true,
            'message' => 'Image Data Updated Successfully',
            'data' => $image,
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
        $image = Image::find($id);
        if (is_null($image)) {
            return response()->json([
                'status' => false,
                'message' => 'image Not Found',
            ]);
        }

        $image->delete();
        return response()->json([
            'status' => true,
            'message' => 'Image Deleted Successfully',
            'data' => $image,
        ]);
    }
}
