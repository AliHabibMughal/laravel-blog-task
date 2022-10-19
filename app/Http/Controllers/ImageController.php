<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Image;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Str;

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
            'message' => 'Image Retrieved Successfully',
            // 'data' => $images,
            'data' => ImageResource::collection($images),
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
        $validateImage = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'src' => 'required|image|max:1048|mimes:jpg,png,jpeg,gif,svg',
                // 'src' => 'required|max:8000|mimes:jpg,png,jpeg,gif,svg,mp4,m4v,avi,flv,mov',
                'post_id' => 'required',
            ],
        );
        if ($validateImage->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Enter Data in All Fields',
                'errors' => $validateImage->errors()
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
                'message' => 'Unable to find this image',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Images are Displayed Successfully',
            'data' => new ImageResource($image),
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
        if (!$image) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to find this image',
            ]);
        }

        $validateImage = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'src' => 'required|image|max:1048|mimes:jpg,png,jpeg,gif,svg',
                // 'src' => 'required|max:8000|mimes:jpg,png,jpeg,gif,svg,mp4,m4v,avi,flv,mov',
                'post_id'=> 'required',
            ],
        );
        if ($validateImage->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Enter Data in All Fields',
                'errors' => $validateImage->errors()
            ], 401);
        }

        $image->delete();
        $image_path = public_path("storage/{$image->src}");
        unlink($image_path);

        // $storage = Storage::disk('public');
        // $imageName = Str::random(32).".".$request->src->getClientOriginalExtenstion();
        // $storage->put($imageName, file_get_contents($request->src));

        $image->title = $request->title;
        $image->alt = $request->alt;
        $image->src = $request->file('src')->store('image', 'public');
        $image->post_id = $request->post_id;
        $image->save();
        return response()->json([
            'status' => true,
            'message' => 'Image Updated Successfully',
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
        if (!$image) {
            return response()->json([
                'status' => false,
                'message' => 'Image Not Found',
            ]);
        }

        $image->delete();
        $image_path = public_path("storage/{$image->src}");
        unlink($image_path);

        return response()->json([
            'status' => true,
            'message' => 'Image Deleted Successfully',
            'data' => $image,
        ]);
    }
}
