<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductPageController extends Controller
{
    /**
     * Display the product detail page.
     */
    public function show($id)
    {
        $product = Product::with(['category', 'merchant', 'images'])
            ->where('visibility', 1)
            ->where('deleted', 0)
            ->where('is_approved', 1)
            ->findOrFail($id);

        // Get related products from same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('visibility', 1)
            ->where('deleted', 0)
            ->where('is_approved', 1)
            ->limit(4)
            ->get();

        $quickOrderEnabled = env('QUICK_ORDER_ENABLED', false);

        return view('product.show', compact('product', 'relatedProducts', 'quickOrderEnabled'));
    }
}
