<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request, $productId)
    {
        $user = $request->user(); // Assumes user is authenticated
        $user->wishlist()->attach($productId);

        return response()->json(['message' => 'Added to wishlist']);
    }

    public function indexByUser(Request $request)
    {
        $user = $request->user();

        $wishlist = $user->wishlist()->with('merchant')->get();
    
        return response()->json($wishlist);
    }
    public function removeFromWishlist(Request $request, $productId)
    {
        $user = $request->user();
        $user->wishlist()->detach($productId);

        return response()->json(['message' => 'Removed from wishlist']);
    }
}
