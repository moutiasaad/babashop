<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * GET /api/products/{product}/reviews
     * Public — returns summary + paginated list.
     */
    public function index(Product $product)
    {
        $reviews = Review::where('product_id', $product->id)
            ->with('user:id,fullname')
            ->latest()
            ->get();

        $total = $reviews->count();
        $average = $total > 0 ? round($reviews->avg('rating'), 1) : 0;

        // count per star level
        $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($reviews as $r) {
            $star = (int) $r->rating;
            if (isset($distribution[$star])) {
                $distribution[$star]++;
            }
        }

        $formatted = $reviews->map(function ($r) {
            return [
                'id'         => $r->id,
                'user_id'    => $r->user_id,
                'user_name'  => $r->user?->fullname ?? 'Anonyme',
                'rating'     => (int) $r->rating,
                'comment'    => $r->comment,
                'created_at' => $r->created_at?->format('Y-m-d'),
            ];
        });

        return response()->json([
            'average'      => $average,
            'total'        => $total,
            'distribution' => $distribution,
            'reviews'      => $formatted,
        ]);
    }

    /**
     * POST /api/products/{product}/reviews
     * Auth — create or update the authenticated user's review.
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating'  => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();

        // upsert — one review per user per product
        $review = Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => $userId],
            ['rating' => (int) round($request->rating), 'comment' => $request->comment],
        );

        return response()->json([
            'id'         => $review->id,
            'user_id'    => $review->user_id,
            'user_name'  => Auth::user()->fullname ?? 'Anonyme',
            'rating'     => (int) $review->rating,
            'comment'    => $review->comment,
            'created_at' => $review->updated_at->format('Y-m-d'),
        ], 201);
    }

    /**
     * DELETE /api/products/{product}/reviews
     * Auth — delete own review.
     */
    public function destroy(Product $product)
    {
        Review::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json(['message' => 'Review deleted']);
    }
}
