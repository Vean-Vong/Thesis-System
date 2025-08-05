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
    public function index(Request $request)
    {
        $result = ['status' => 200];

        try {
            $query = Product::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('model', 'like', "%$search%")
                        ->orWhere('colors', 'like', "%$search%")
                        ->orWhere('filtration_stage', 'like', "%$search%")
                        ->orWhere('cold_water_tank_capacity', 'like', "%$search%")
                        ->orWhere('hot_water_tank_capacity', 'like', "%$search%")
                        ->orWhere('heating_capacity', 'like', "%$search%")
                        ->orWhere('cooling_capacity', 'like', "%$search%")
                        ->orWhere('cold_power_consumption', 'like', "%$search%")
                        ->orWhere('hot_power_consumption', 'like', "%$search%")
                        ->orWhere('quantity', 'like', "%$search%");
                });
            }

            $perPage = $request->input('limit', 10);

            // Load stocks relation
            $products = $query->with('stocks')->latest()->paginate($perPage);

            // Transform collection items
            $products->getCollection()->transform(function ($product) {

                $stockSum = $product->stocks->sum('quantity');
                return [
                    'id' => $product->id,
                    'model' => $product->model,
                    'colors' => $product->colors,
                    'filtration_stage' => $product->filtration_stage,
                    'cold_water_tank_capacity' => $product->cold_water_tank_capacity,
                    'hot_water_tank_capacity' => $product->hot_water_tank_capacity,
                    'heating_capacity' => $product->heating_capacity,
                    'cooling_capacity' => $product->cooling_capacity,
                    'cold_power_consumption' => $product->cold_power_consumption,
                    'hot_power_consumption' => $product->hot_power_consumption,
                    'price' => $product->price,
                    'rental_price' => $product->rental_price,
                    'install_price' => $product->install_price,
                    'image' => $product->image,
                    'quantity' => $product->quantity,
                    'stock_quantity' => $stockSum > 0 ? $stockSum : $product->quantity,
                    'total_stock' => $product->total_stock,  // uses accessor
                    'stock_status' => $product->stock_status, // uses accessor
                ];
            });

            $result['data'] = $products;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }


    public function stockQuantities()
    {
        try {
            $products = Product::with('stocks')
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'stock_quantity' => $product->stocks->sum('quantity'),
                    ];
                });

            return response()->json([
                'status' => 200,
                'data' => $products,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = ['status' => 200];

        try {
            $validated = $request->validate([
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'model' => 'required|string|max:255',
                'colors' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'rental_price' => 'nullable|numeric|min:0',
                'install_price' => 'nullable|numeric|min:0',
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

            // Handle multiple image uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $imagePaths[] = $file->store('product_images', 'public');
                }
            }

            $validated['images'] = $imagePaths; // Save as JSON array


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
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'model' => 'required|string|max:255',
                'colors' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'rental_price' => 'nullable|numeric|min:0',
                'install_price' => 'nullable|numeric|min:0',
                'filtration_stage' => 'required|string|max:255',
                'cold_water_tank_capacity' => 'required|string|max:255',
                'hot_water_tank_capacity' => 'required|string|max:255',
                'heating_capacity' => 'required|string|max:255',
                'cooling_capacity' => 'required|string|max:255',
                'cold_power_consumption' => 'required|string|max:255',
                'hot_power_consumption' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
            ]);

            $imagePaths = [];
            if ($request->hasFile('images')) {
                if ($product->images && is_array($product->images)) {
                    foreach ($product->images as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
                foreach ($request->file('images') as $file) {
                    $imagePaths[] = $file->store('product_images', 'public');
                }
                $validated['images'] = $imagePaths;  // Assign new images here
            } else {
                $validated['images'] = $product->images; // keep old images if no new ones
            }

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
