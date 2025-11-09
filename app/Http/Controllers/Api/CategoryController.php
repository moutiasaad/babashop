<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
     // Get all categories
    public function index()
    {
        $categories = Category::where("visibility", 1)
            ->with('subcategories') // Load subcategories relationship
            ->orderBy('order_item')
            ->get();

        return $categories;
    }      
 
     // Get a specific category by ID
     public function show(Category $category)
    {
        // Load subcategories relationship
        $category->load('subcategories');

        return response()->json($category);
    }

    // Get all products in a category
public function getProductsByCategory(Category $category, Request $request)
{
    // Get pagination parameters
    $page = $request->get('page', 1);
    $perPage = $request->get('per_page', 2); // Default 16 items per page
    $offset = ($page - 1) * $perPage;
    $subCatId = $request->get('subCat'); // Subcategory ID filter
    $priceOrder = $request->get('price'); // Price sorting: 'asc' or 'desc'

    // Special case: if category ID is 39, show all products with discount price
    
    // Regular category logic for other categories
    if ($subCatId) {
        // CASE 1: User selected a subcategory - products MUST have BOTH parent AND subcategory
        // Verify the subcategory is associated with this parent category
        $subcategory = $category->subcategories()->where('category.id', $subCatId)->first();

        if (!$subcategory) {
            // Invalid subcategory for this parent category
            return response()->json([
                'data' => [],
                'pagination' => [
                    'current_page' => (int) $page,
                    'per_page' => (int) $perPage,
                    'total' => 0,
                    'total_pages' => 0,
                    'has_more' => false,
                    'from' => 0,
                    'to' => 0
                ]
            ]);
        }

        // Get products that have BOTH parent category AND subcategory
        // Option 1: category_id = parent AND other_categories contains subcat
        $option1Ids = Product::where('category_id', $category->id)
            ->whereJsonContains('other_categories', (string) $subCatId)
            ->where('visibility', 1)
            ->where('deleted', 0)
            ->whereHas('merchant', function ($q) {
                $q->where('visibility', 1);
            })
            ->pluck('id');

        // Option 2: category_id = subcat AND other_categories contains parent
        $option2Ids = Product::where('category_id', $subCatId)
            ->whereJsonContains('other_categories', (string) $category->id)
            ->where('visibility', 1)
            ->where('deleted', 0)
            ->whereHas('merchant', function ($q) {
                $q->where('visibility', 1);
            })
            ->pluck('id');

        // Option 3: other_categories contains BOTH parent and subcat
        $option3Ids = Product::whereJsonContains('other_categories', (string) $category->id)
            ->whereJsonContains('other_categories', (string) $subCatId)
            ->where('visibility', 1)
            ->where('deleted', 0)
            ->whereHas('merchant', function ($q) {
                $q->where('visibility', 1);
            })
            ->pluck('id');

        // Merge all options and get unique IDs
        $allProductIds = $option1Ids->merge($option2Ids)->merge($option3Ids)->unique()->values();
        $totalCount = $allProductIds->count();
    } else {
        // CASE 2: No subcategory selected - products that have parent category (regardless of subcategories)
        // Get primary products where category_id = parent
        $primaryProductIds = Product::where('category_id', $category->id)
            ->where('visibility', 1)
            ->where('deleted', 0)
            ->whereHas('merchant', function ($q) {
                $q->where('visibility', 1);
            })
            ->pluck('id');

        // Get products where parent is in other_categories
        $otherProductIds = Product::whereJsonContains('other_categories', (string) $category->id)
            ->where('visibility', 1)
            ->where('deleted', 0)
            ->whereHas('merchant', function ($q) {
                $q->where('visibility', 1);
            })
            ->pluck('id');

        // Merge and get unique product IDs
        $allProductIds = $primaryProductIds->merge($otherProductIds)->unique()->values();
        $totalCount = $allProductIds->count();
    }
    
    // Fetch all products to apply price sorting
    $allProducts = Product::whereIn('id', $allProductIds)
        ->with('merchant')
        ->get();

    // Apply price sorting if specified
    if ($priceOrder === 'asc' || $priceOrder === 'desc') {
        // Sort by effective price (discount_price if available and valid, otherwise price)
        $allProducts = $allProducts->sort(function($a, $b) use ($priceOrder) {
            // Calculate effective price for product A
            $priceA = $a->price;
            if ($a->discount_price && $a->discount_price > 0) {
                // Check if discount is currently valid
                $isDiscountValid = true;
                if ($a->discount_start && strtotime($a->discount_start) > time()) {
                    $isDiscountValid = false;
                }
                if ($a->discount_end && strtotime($a->discount_end) < time()) {
                    $isDiscountValid = false;
                }
                if ($isDiscountValid) {
                    $priceA = $a->discount_price;
                }
            }

            // Calculate effective price for product B
            $priceB = $b->price;
            if ($b->discount_price && $b->discount_price > 0) {
                // Check if discount is currently valid
                $isDiscountValid = true;
                if ($b->discount_start && strtotime($b->discount_start) > time()) {
                    $isDiscountValid = false;
                }
                if ($b->discount_end && strtotime($b->discount_end) < time()) {
                    $isDiscountValid = false;
                }
                if ($isDiscountValid) {
                    $priceB = $b->discount_price;
                }
            }

            // Compare based on order direction
            if ($priceOrder === 'asc') {
                return $priceA <=> $priceB;
            } else {
                return $priceB <=> $priceA;
            }
        })->values();
    }

    // Apply pagination after sorting
    $products = $allProducts->slice($offset, $perPage)->values();

    // Convert to arrays to avoid circular reference in JSON encoding
    $productsArray = $products->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'discount_price' => $product->discount_price,
            'discount_start' => $product->discount_start,
            'discount_end' => $product->discount_end,
            'image' => $product->image,
            'merchant' => $product->merchant ? [
                'id' => $product->merchant->id,
                'name' => $product->merchant->name,
                'visibility' => $product->merchant->visibility,
            ] : null,
        ];
    });

    // Always return valid JSON (even if collection is empty)
    return response()->json([
        'data' => $productsArray,
        'pagination' => [
            'current_page' => (int) $page,
            'per_page' => (int) $perPage,
            'total' => $totalCount,
            'total_pages' => ceil($totalCount / $perPage),
            'has_more' => $page * $perPage < $totalCount,
            'from' => $totalCount > 0 ? $offset + 1 : 0,
            'to' => min($offset + $perPage, $totalCount)
        ]
    ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

}

}
