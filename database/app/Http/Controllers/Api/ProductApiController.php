<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * Get all products for the catalog
     */
    public function index()
    {
        try {
            // Including categories, images, and variants with sizes
            $products = Product::with(['categories', 'images', 'variants.size'])->get();
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detail of a specific product
     */
    public function show($id)
    {
        try {
            $product = Product::with(['categories', 'images', 'variants.size'])->find($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch product details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
