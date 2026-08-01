<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request, $productId)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        // syncWithoutDetaching keeps existing wishlist rows untouched and
        // adds this one only if it isn't already there. Avoids the duplicate
        // pivot rows that plain attach() creates on repeated taps.
        $user->wishlist()->syncWithoutDetaching([$productId]);

        return response()->json(['success' => true, 'is_wishlisted' => true, 'message' => 'Added to wishlist']);
    }

    public function indexByUser(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // The Product model's is_wishlisted accessor uses Auth::user() and
        // will return true for every row here (since they're all wishlisted).
        $wishlist = $user->wishlist()->with('merchant')->get();

        return response()->json($wishlist);
    }

    public function removeFromWishlist(Request $request, $productId)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        $user->wishlist()->detach($productId);

        return response()->json(['success' => true, 'is_wishlisted' => false, 'message' => 'Removed from wishlist']);
    }
}
