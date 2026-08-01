<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Convert any stored image path/URL to one accessible from the current request host.
     * Handles three cases:
     *   - External CDN URL (LoremFlickr, Unsplash, brand shops etc.) → pass through
     *     UNCHANGED. Stripping the host would break the URL and drop query params
     *     like `?lock=` used by seeded placeholders.
     *   - Full URL stored with our own host (localhost, babashop.store, shaieb.store) →
     *     rewrite host to the current request so Flutter devices using LAN IPs
     *     still get reachable URLs.
     *   - Relative path (e.g. uploads/products/file.jpg) → prepend current request host.
     */
    private function imgUrl(?string $stored): ?string
    {
        if (!$stored) return null;

        if (str_starts_with($stored, 'http')) {
            $host = strtolower(parse_url($stored, PHP_URL_HOST) ?? '');
            $ownHosts = [
                'babashop.store', 'www.babashop.store',
                'shaieb.store', 'www.shaieb.store',
                '127.0.0.1', 'localhost',
            ];
            $isOwnHost = in_array($host, $ownHosts, true)
                || str_starts_with($host, '192.168.')
                || str_starts_with($host, '10.');
            if (!$isOwnHost) {
                // External CDN — return the URL exactly as stored.
                return $stored;
            }
            $path = parse_url($stored, PHP_URL_PATH);
        } else {
            $path = '/' . ltrim($stored, '/');
        }

        return $path ? (request()->getSchemeAndHttpHost() . $path) : null;
    }

    public function index(Request $request)
    {
        $userZoneId = null;
        if ($request->has('userid') && $request->userid) {
            $user = User::find($request->userid);
            $userZoneId = $user ? $user->zone_id : null;
        } elseif (Auth::check()) {
            $userZoneId = Auth::user()->zone_id;
        }

        $query = Product::where('visibility', 1)->where('deleted', 0);

        if ($userZoneId) {
            $query->whereHas('merchant', function ($q) use ($userZoneId) {
                $q->where('visibility', 1)
                  ->whereHas('zones', fn($z) => $z->where('zones.id', $userZoneId));
            });
        } else {
            $query->whereHas('merchant', fn($q) => $q->where('visibility', 1));
        }

        $with = ['merchant', 'options.optionValues'];
        if ($request->price == 'desc') {
            $products = $query->with($with)->orderBy('price', 'desc')->limit(8)->get();
        } elseif ($request->price == 'asc') {
            $products = $query->with($with)->orderBy('price', 'asc')->limit(8)->get();
        } else {
            $products = $query->with($with)->inRandomOrder()->limit(8)->get();
        }

        $result = $products->map(function ($p) {
            // First color-option image (display only, no qty check)
            $colorThumbnail = null;
            foreach ($p->options as $opt) {
                $n = strtolower($opt->name ?? '');
                if (str_contains($n, 'couleur') || str_contains($n, 'color')) {
                    foreach ($opt->optionValues as $val) {
                        if ($val->image_path) {
                            $colorThumbnail = $this->imgUrl($val->image_path);
                            break;
                        }
                    }
                    if ($colorThumbnail) break;
                }
            }

            $p->unsetRelation('options');

            // Main image — rewrite host
            $raw = $p->getRawOriginal('image') ?? '';
            $imageUrls = $raw ? [$this->imgUrl($raw)] : [];

            $data = [
                'id'             => $p->id,
                'name'           => $p->name,
                'qty'            => (int) $p->getRawOriginal('qty'),
                'image'          => array_filter($imageUrls),
                'description'    => $p->description,
                'type'           => $p->type ?? '',
                'sku'            => $p->sku ?? '',
                'category_id'    => $p->category_id,
                'price'          => (float) $p->price,
                'discount_price' => (float) $p->discount_price,
                'merchant'       => $p->merchant ? [
                    'id'         => $p->merchant->id,
                    'brand_name' => $p->merchant->brand_name,
                ] : null,
                'color_thumbnail' => $colorThumbnail,
            ];

            return $data;
        });

        return response()->json($result);
    }

    public function show(Product $product)
    {
        $product->load('merchant', 'options.optionValues', 'images', 'attachments');

        // Options — rewrite image_url host
        $options = $product->options->map(fn($opt) => [
            'id'     => $opt->id,
            'name'   => $opt->name,
            'values' => $opt->optionValues->map(fn($v) => [
                'id'        => $v->id,
                'value'     => $v->value,
                'qty'       => (int) $v->qty,
                'image_url' => $this->imgUrl($v->image_path),
            ])->values()->all(),
        ])->values()->all();

        // Variants
        $variants = [];
        try {
            $variants = $product->variants()->get()->map(fn($v) => [
                'id'         => $v->id,
                'options'    => $v->options,
                'qty'        => $v->qty,
                'price'      => $v->price,
                'sku'        => $v->sku,
                'image_path' => $this->imgUrl($v->image_path),
            ])->values()->all();
        } catch (\Throwable $e) {}

        // Gallery — rewrite host
        $gallery = $product->images->map(fn($img) => [
            'image_path' => $this->imgUrl($img->image_path),
        ])->values()->all();

        // Main image — attachments first, then raw column; always rewrite host
        if ($product->attachments->isNotEmpty()) {
            $imageUrls = $product->attachments->map(
                fn($a) => $this->imgUrl("/uploads/{$a->folder}/{$a->name}")
            )->filter()->values()->all();
        } else {
            $raw = $product->getRawOriginal('image') ?? '';
            $imageUrls = $raw ? array_filter([$this->imgUrl($raw)]) : [];
        }

        $merchant = $product->merchant ? [
            'id'         => $product->merchant->id,
            'brand_name' => $product->merchant->brand_name,
        ] : null;

        return response()->json(['data' => [
            'id'             => $product->id,
            'name'           => $product->name,
            'qty'            => (int) $product->getRawOriginal('qty'),
            'image'          => array_values($imageUrls),
            'gallery'        => $gallery,
            'description'    => $product->description,
            'type'           => $product->type,
            'sku'            => $product->sku,
            'category_id'    => $product->category_id,
            'price'          => (float) $product->price,
            'discount_price' => (float) $product->discount_price,
            'discount_start' => $product->discount_start,
            'discount_end'   => $product->discount_end,
            'is_wishlisted'  => $product->is_wishlisted,
            'merchant'       => $merchant,
            'options'        => $options,
            'variants'       => $variants,
        ]], 200);
    }

    // ── Product Options CRUD ─────────────────────────────────────────────────

    public function listOptions(Product $product)
    {
        return response()->json($product->options);
    }

    public function storeOption(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'values'   => 'required|array|min:1',
            'values.*' => 'string',
        ]);

        $option = $product->options()->create($validated);
        return response()->json($option, 201);
    }

    public function updateOption(Request $request, Product $product, ProductOption $option)
    {
        abort_if($option->product_id !== $product->id, 404);

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:100',
            'values'   => 'sometimes|array|min:1',
            'values.*' => 'string',
        ]);

        $option->update($validated);
        return response()->json($option);
    }

    public function destroyOption(Product $product, ProductOption $option)
    {
        abort_if($option->product_id !== $product->id, 404);
        $option->delete();
        return response()->json(['message' => 'Option deleted']);
    }

    public function searchByName($name, Request $request)
    {
        $userZoneId = null;
        if ($request->has('userid') && $request->userid) {
            $user = User::find($request->userid);
            $userZoneId = $user ? $user->zone_id : null;
        } elseif (Auth::check()) {
            $userZoneId = Auth::user()->zone_id;
        }

        $query = Product::where('visibility', 1)
            ->where('deleted', 0)
            ->where('name', 'like', '%' . $name . '%');

        if ($userZoneId) {
            $query->whereHas('merchant', function ($q) use ($userZoneId) {
                $q->where('visibility', 1)
                  ->whereHas('zones', fn($z) => $z->where('zones.id', $userZoneId));
            });
        }

        $products = $query->with('merchant')->get();
        return response()->json($products);
    }
}
