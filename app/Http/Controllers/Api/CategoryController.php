<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories =  Category::all();
        if ($categories->count() > 0) {
            return response()->json($categories, 200);
        } else {
            return response()->json([
                'message' => "No category found"
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'max:50', 'unique:categories,name']
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        } else {
            try {
                Category::create([
                    'name' => $request->name
                ]);
                return response()->json([
                    'message' => 'Category created successfully'
                ], 201);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'max:50', 'unique:categories,name,' . $id]
        ]);

        // Check if That Category Exists
        $category = Category::find($id);
        if ($category  == null) {
            return response()->json([
                'message' => 'Category Not Found'
            ], 404);
        }

        // Chech From The Validation
        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        } else {
            try {
                $category->update([
                    'name' => $request->name
                ]);
                return response()->json([
                    'message' => 'Updated successfully'
                ], 201);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if ($category == null) {
            return response()->json([
                'message' => 'Category Not Found'
            ], 404);
        }

        try {
            $category->delete();
            return response()->json([
                'message' => 'Deleted successfully'
            ], 202);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
