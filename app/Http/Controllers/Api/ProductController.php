<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiForamtProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products =  Product::all();
        if ($products->count() > 0) {
            return response()->json(ApiForamtProduct::collection($products), 200);
        } else {
            return response()->json([
                'message' => "No product found"
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => ['required', 'int', 'exists:categories,id'],
            'name' => ['required', 'min:3', 'max:255', 'unique:products,name'],
            'buy_price' => ['required', 'decimal:0,2'],
            'min_sale' => ['required', 'decimal:0,2'],
            'normal_sale' => ['required', 'decimal:0,2'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        } else {
            try {
                Product::create($validate->validated());
                return response()->json([
                    'message' => 'Product created successfully'
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
            'name' => ['required', 'min:3', 'max:50', 'unique:products,name,' . $id]
        ]);

        // Check if That Product Exists
        $product = Product::find($id);
        if ($product == null) {
            return response()->json([
                'message' => 'Product Not Found'
            ], 404);
        }

        // Chech From The Validation
        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        } else {
            try {
                $product->update($validate->validated());
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
        $product = Product::find($id);
        if ($product == null) {
            return response()->json([
                'message' => 'Product Not Found'
            ], 404);
        }

        try {
            $product->delete();
            return response()->json([
                'message' => 'Deleted successfully'
            ], 202);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
