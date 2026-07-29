<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    /**
     * Get all active banners
     * Returns enabled status and list of visible banners
     */
    public function index()
    {
        // Get all visible (non-deleted) banners ordered by order_item
        $banners = Banner::where('deleted', 0)
            ->where('visibility', 1)
            ->orderBy('order_item')
            ->get(['id', 'name', 'image', 'link', 'order_item']);

        // Check if there are any active banners
        $enabled = $banners->count() > 0;

        return response()->json([
            'success' => true,
            'enabled' => $enabled,
            'banners' => $banners
        ]);
    }
}
