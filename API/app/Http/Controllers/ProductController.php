<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = ['status' => 200];

        try {
            $products = Product::latest()->paginate(10);
            $result['data'] = $products;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = ['status' => 200];

        try {
            $validated = $request->validate([
                'image' => 'nullable|image|max:2048',
                'model' => 'required|string|max:255',
                'colors' => 'required|string|max:255',
                'filtration_stage' =>  'required|string|max:255',
                'cold_water_tank_capacity' => 'required|string|max:255',
                'hot_water_tank_capacity' => 'required|string|max:255',
                'heating_capacity' => 'required|string|max:255',
                'cooling_capacity' => 'required|string|max:255',
                'cold_power_consumption' => 'required|string|max:255',
                'hot_power_consumption' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('product_images', 'public');
                $validated['image'] = $imagePath;
            }

            $product = Product::create($validated);
            $result['data'] = $product;
            $result['message'] = 'Product created successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            Log::error('Error creating product: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            $result['status'] = 500;
            $result['message'] = 'An error occurred while creating the product.';
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $product;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $result = ['status' => 200];

        try {
            $validated = $request->validate([
                'image' => 'nullable|image|max:2048',
                'model' => 'required|string|max:255',
                'colors' => 'required|string|max:255',
                'filtration_stage' => 'required|string|max:255',
                'cold_water_tank_capacity' => 'required|string|max:255',
                'hot_water_tank_capacity' => 'required|string|max:255',
                'heating_capacity' => 'required|string|max:255',
                'cooling_capacity' => 'required|string|max:255',
                'cold_power_consumption' => 'required|string|max:255',
                'hot_power_consumption' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
            ]);

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($product->image) {
                    Storage::delete('public/' . $product->image);
                }

                // Store new image
                $imagePath = $request->file('image')->store('product_images', 'public');
                $validated['image'] = $imagePath;
            }

            // Update the product with new data
            $product->update($validated);
            $result['data'] = $product;
            $result['message'] = 'Product updated successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            Log::error('Error updating product: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            $result['status'] = 500;
            $result['message'] = 'An error occurred while updating the product.';
        }

        return response()->json($result);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $result = ['status' => 200];

        try {
            // Delete the image if it exists
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }

            $product->delete();
            $result['message'] = 'Product deleted successfully!';
        } catch (\Throwable $e) {
            Log::error('Error deleting product: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            $result['status'] = 500;
            $result['message'] = 'An error occurred while deleting the product.';
        }

        return response()->json($result);
    }
}