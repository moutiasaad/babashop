<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
   public function index(Request $request)
    {
            // Get user's zone_id for filtering
            $userZoneId = null;
            if ($request->has('userid') && $request->userid) {
                $user = User::find($request->userid);
                $userZoneId = $user ? $user->zone_id : null;
            } elseif (Auth::check()) {
                $userZoneId = Auth::user()->zone_id;
            }

            // Base query with zone filtering
            $query = Product::where('visibility', 1)
                ->where('deleted', 0);

            // Filter by user's zone if zone_id exists
            if ($userZoneId) {
                $query->whereHas('merchant', function ($q) use ($userZoneId) {
                    $q->where('visibility', 1)
                      ->whereHas('zones', function ($zoneQuery) use ($userZoneId) {
                          $zoneQuery->where('zones.id', $userZoneId);
                      });
                });
            } else {
                // No zone filter, just check merchant visibility
                $query->whereHas('merchant', function ($q) {
                    $q->where('visibility', 1);
                });
            }

            // Apply sorting
            if ($request->price == 'desc') {
                $products = $query->with('merchant')->orderBy('price', 'desc')->limit(8)->get();
            } elseif ($request->price == 'asc') {
                $products = $query->with('merchant')->orderBy('price', 'asc')->limit(8)->get();
            } else {
                $products = $query->with('merchant')->inRandomOrder()->limit(8)->get();
            }

            return response()->json($products);
    }

    public function show(Product $product)
    {
        // Eager-load relationships
        $product->load('merchant', 'category');

        // Return clean JSON
        return response()->json([
            'data' => $product->toArray()
        ], 200);
    }


    public function searchByName($name, Request $request)
    {
        // Get user's zone_id for filtering
        $userZoneId = null;
        if ($request->has('userid') && $request->userid) {
            $user = User::find($request->userid);
            $userZoneId = $user ? $user->zone_id : null;
        } elseif (Auth::check()) {
            $userZoneId = Auth::user()->zone_id;
        }

        // Base query
        $query = Product::where('visibility', 1)
            ->where('deleted', 0)
            ->where('name', 'like', '%' . $name . '%');

        // Filter by user's zone if zone_id exists
        if ($userZoneId) {
            $query->whereHas('merchant', function ($q) use ($userZoneId) {
                $q->where('visibility', 1)
                  ->whereHas('zones', function ($zoneQuery) use ($userZoneId) {
                      $zoneQuery->where('zones.id', $userZoneId);
                  });
            });
        }

        $products = $query->with('merchant')->get();
        return response()->json($products);
    }
}

