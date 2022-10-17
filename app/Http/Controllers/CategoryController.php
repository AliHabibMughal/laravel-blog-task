<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all();
        return response()->json([
            'status' => true,
            'message' => 'All Categories Retrieved Successfully',
            'data' => CategoryResource::collection($category),
            // 'data' => $category,
        ]);
        // $posts = Post::all();
        // $category = Category::with('posts')->find(5);
        // $category->posts()->sync($posts);
        // return $category;
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
                'name' => 'required|max:255',
            ],
        );
        if ($validatePost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Enter Category Name',
                'errors' => $validatePost->errors()
            ], 401);
        }

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category Created Successfully',
            'data' => $category,
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
        $category = Category::with('posts')->find($id);
        if (is_null($category)) {
            return response()->json([
                'status' => false,
                'message' => 'Category Not Found',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Category with posts retrieved successfully',
            'data' => $category,
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
        $category = Category::find($id);
        if (is_null($category)) {
            return response()->json([
                'status' => false,
                'message' => 'Category Not Found',
            ]);
        }
        $validateCategory = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
            ],
        );
        if ($validateCategory->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Enter Category Name',
                'errors' => $validateCategory->errors()
            ], 401);
        }
        $category->name = $request->name;
        $category->save();
        return response()->json([
            'status' => true,
            'message' => 'Category Updated Successfully',
            'data' => $category,
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
        $category = Category::find($id);
        if (is_null($category)) {
            return response()->json([
                'status' => false,
                'message' => 'Category Not Found',
            ]);
        }
        $category->delete();
        return response()->json([
            'status' => true,
            'message' => 'Category Deleted Successfully',
            'data' => $category,
        ]);
    }
}
