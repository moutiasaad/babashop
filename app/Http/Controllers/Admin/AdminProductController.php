<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Category;
use App\Models\Country;
use App\Models\Merchant;
use App\Models\Attachment;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Imports\ProductImport;
use Illuminate\Support\Str;


class AdminProductController extends Controller
{
    const INDEX_ROUTE = 'admin.product.index';
    const ADD_ROUTE = 'admin.products.add';
    const STORE_ROUTE = 'admin.products.store';
    const DESTROY_ROUTE = 'admin.products.destroy';
    const INDEX_VIEW = 'admin.product.index-fr';
    const ADD_VIEW = 'admin.product.add-fr';
    const EDIT_VIEW = 'admin.product.edit-fr';
    const INDEX_APPROVE_VIEW = 'admin.product.index_toapprove-fr';
    const MODEL = \App\Models\Product::class;
    const MODEL_NAME = 'Product';
    const MODEL_NAME_PLURAL = 'products';

    public function indexApprove(Request $request ,$type = null)
    {
        $query = app(self::MODEL)::query();
        $query->where('deleted',0);
        if (request()->has('visibility')) {
        $query->where('visibility', 0);
        }

        // Apply merchant_id filter if the user has role_id == 2
        if (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role_id == 2) {
            $query->where('merchant_id', auth()->guard('admin')->user()->merchant_id);
        }


            $query->where('is_approved', 0);


        // Handle search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }
        $query->with('merchant');

        // Pagination
        $items = $query->paginate($request->get('per_page', 10)); // Default to 10 per page
        $categories = Category::where('deleted', 0)->get();
        $merchants = Merchant::all();
        $type = 0;

        return view(self::INDEX_APPROVE_VIEW, compact('items','type','categories','merchants'));
    }

    public function index(Request $request, $type = null)
    {
        $categories = Category::where('deleted', 0)->get();
        $merchants = Merchant::all();

        if ($request->ajax()) {
            $query = app(self::MODEL)::query();
            $query->where('deleted', 0);

            // Show only approved products by default
            if ($request->has('not_approved')) {
                $query->where('is_approved', 0);
            } else {
                $query->where('is_approved', 1);
            }

            if ($request->has('visibility')) {
                $query->where('visibility', 0);
            }

            if ($request->filled('merchant_id')) {
                $query->where('merchant_id', $request->merchant_id);
            } elseif (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role_id == 2) {
                $query->where('merchant_id', auth()->guard('admin')->user()->merchant_id);
            }

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            }

            if ($request->filled('category_id')) {
                $categoryId = (int) $request->category_id;

                $query->where(function ($q) use ($categoryId) {
                    $q->whereJsonContains('other_categories', $categoryId)
                      ->orWhere('category_id', $categoryId);
                });
            }

            $query->with('merchant');

            $items = $query->paginate($request->get('per_page', 10));

            return response()->json([
                'data' => $items->items(),
                'pagination' => [
                    'current_page' => $items->currentPage(),
                    'last_page' => $items->lastPage(),
                    'total' => $items->total(),
                    'per_page' => $items->perPage(),
                    'next_page_url' => $items->nextPageUrl(),
                    'prev_page_url' => $items->previousPageUrl(),
                ]
            ]);
        }

        return view(self::INDEX_VIEW, compact('type', 'categories', 'merchants'));
    }


        public function showAdd()
    {
        $categories = Category::where('deleted', 0)->get();
        $merchants = Merchant::all();
        $type = 0; // Default type for backward compatibility
        return view(self::ADD_VIEW , compact('categories','merchants','type') );
    }

    public function store(Request $request)
    {
        // Validate the form input
        if (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role_id == 2) {
            $request['merchant_id'] = auth()->guard('admin')->user()->merchant_id;
        }
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'qty' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'required|string',
            'type' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'category_id' => 'required|exists:category,id',
            'other_categories' => 'nullable|array',
            'other_categories.*' => 'exists:category,id',
            'merchant_id' => 'required|exists:merchant,id',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric|min:0',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after:discount_start',
        ], [
            'name.required' => 'Le nom est requis',
            'description.required' => 'La description est requise',
            'qty.required' => 'La quantité est requise',
            'name.string' => 'Le nom doit être du texte',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères',
            'image.required' => 'L\'image est requise',
            'type.required' => 'Le type est requis',
            'sku.required' => 'Le SKU est requis',
            'category_id.required' => 'La catégorie est requise',
            'merchant_id.required' => 'La boutique est requise',
            'price.required' => 'Le prix est requis',
            'discount_price.required' => 'Le prix de réduction est requis',
            'discount_start.required' => 'La date de début de réduction est requise',
            'discount_end.required' => 'La date de fin de réduction est requise',
        ]);
    
        // Handle image upload
        $fileExtension = strtolower($request->file('image')->getClientOriginalExtension());
        $fileName = time() . '.' . $fileExtension;
        $destinationPath = public_path('uploads/products');
    
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true); // Ensure the directory exists
        }
    
        $request->file('image')->move($destinationPath, $fileName);
        $imagePath = "/uploads/products/" . $fileName;
    
        // Determine approval status based on user role
        // Admin (role_id == 1) products are auto-approved and visible
        // Merchant (role_id == 2) products need approval
        $isApproved = (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role_id == 1) ? 1 : 0;
        $visibility = (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role_id == 1) ? 1 : 0;

        // Create new record
        $product = app(self::MODEL)::create([
            'name' => $validated['name'],
            'image' => $imagePath, // Store image path in DB
            'description' => $validated['description'],
            'qty' => $validated['qty'],
            'type' => $validated['type'],
            'sku' => $validated['sku'],
            'category_id' => $validated['category_id'], // Primary category
            'other_categories' => $request->input('other_categories', []), // Additional categories
            'merchant_id' => $validated['merchant_id'],
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'],
            'discount_start' => $validated['discount_start'],
            'discount_end' => $validated['discount_end'],
            'product_type' => 0,
            'visibility' => $visibility,
            'deleted' => 0,
            'is_approved' => $isApproved,
        ]);

        if ($request->has('attachments')) {
            foreach ($request->attachments as $fileId) {
                $attachment = Attachment::find($fileId);
                if ($attachment) {
                    $attachment->file = $product->id;
                    $attachment->save();
                }
            }
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $sortOrder = 1;
            foreach ($request->file('gallery_images') as $galleryImage) {
                $galleryExtension = strtolower($galleryImage->getClientOriginalExtension());
                $galleryFileName = time() . '_' . $sortOrder . '.' . $galleryExtension;
                $galleryImage->move($destinationPath, $galleryFileName);
                $galleryImagePath = "/uploads/products/" . $galleryFileName;

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $galleryImagePath,
                    'sort_order' => $sortOrder,
                    'is_primary' => 0,
                ]);
                $sortOrder++;
            }
        }

        // ── Save product options + values ────────────────────────────────────
        $submittedOptions = $request->input('options', []);
        foreach ($submittedOptions as $optData) {
            $name          = trim($optData['name'] ?? '');
            $valuesPayload = $optData['values'] ?? [];
            if (!$name || empty($valuesPayload)) continue;

            $opt = ProductOption::create(['product_id' => $product->id, 'name' => $name, 'values' => []]);

            foreach ($valuesPayload as $vData) {
                $value = trim($vData['value'] ?? '');
                if (!$value) continue;
                ProductOptionValue::create([
                    'product_option_id' => $opt->id,
                    'value'             => $value,
                    'qty'               => max(0, (int)($vData['qty'] ?? 0)),
                    'image_path'        => $vData['image_path'] ?? null,
                ]);
            }

            $opt->update(['values' => ProductOptionValue::where('product_option_id', $opt->id)
                ->pluck('value')->toArray()]);
        }
        // ─────────────────────────────────────────────────────────────────────
        $this->syncVariants($product);

        return redirect()->route('admin.product.index')
            ->with('success', 'Produit ajouté avec succès');
    }

    public function showEdit($id)
    {
        $product = app(self::MODEL)::with('options.optionValues')->findOrFail($id);
        $categories = Category::where('deleted', 0)->get();
        $merchants = Merchant::all();
        $productImages = ProductImage::where('product_id', $id)->orderBy('sort_order')->get();
        return view(self::EDIT_VIEW, compact('product', 'categories', 'merchants', 'productImages'));
    }
    
        public function update(Request $request, $id)
            {
                $product = app(self::MODEL)::findOrFail($id);
            
                if (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role_id == 2) {
                    $request['merchant_id'] = auth()->guard('admin')->user()->merchant_id;
                }
            
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'description' => 'required|string',
                    'type' => 'required|string|max:255',
                    'sku' => 'required|string|max:255',
                    'qty' => 'required|string|max:255',
                    'category_id' => 'required|exists:category,id',
                    'other_categories' => 'nullable|array',
                    'other_categories.*' => 'exists:category,id',
                    'merchant_id' => 'required|exists:merchant,id',
                    'price' => 'required|numeric',
                    'discount_price' => 'nullable|numeric|min:0',
                    'discount_start' => 'nullable|date',
                    'discount_end' => 'nullable|date|after:discount_start',
                    'gallery_images' => 'nullable|array',
                    'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                    'delete_images' => 'nullable|array',
                ]);
                
                if($validated['discount_price'] == $validated['price']){
                    $validated['discount_price'] = null ;
                }
                // Handle attachments if present
                if ($request->has('attachments')) {
                    Attachment::where('file', (string)$product->id)->update(['file' => 0, 'updated_at' => now()]);
                    foreach ($request->attachments as $fileId) {
                        $attachment = Attachment::find($fileId);
                        if ($attachment) {
                            $attachment->file = $product->id;
                            $attachment->save();
                        }
                    }
                }
                $validated['other_categories'] = $request->input('other_categories', []);

                // Update the product's basic attributes
                $product->update($validated);

                // Handle deleting selected images
                if ($request->has('delete_images') && is_array($request->delete_images)) {
                    ProductImage::whereIn('id', $request->delete_images)->where('product_id', $id)->delete();
                }

                // Handle new gallery images upload
                if ($request->hasFile('gallery_images')) {
                    $destinationPath = public_path('uploads/products');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $maxSortOrder = ProductImage::where('product_id', $id)->max('sort_order') ?? 0;
                    $sortOrder = $maxSortOrder + 1;

                    foreach ($request->file('gallery_images') as $galleryImage) {
                        $galleryExtension = strtolower($galleryImage->getClientOriginalExtension());
                        $galleryFileName = time() . '_' . $sortOrder . '_' . uniqid() . '.' . $galleryExtension;
                        $galleryImage->move($destinationPath, $galleryFileName);
                        $galleryImagePath = "/uploads/products/" . $galleryFileName;

                        ProductImage::create([
                            'product_id' => $id,
                            'image_path' => $galleryImagePath,
                            'sort_order' => $sortOrder,
                            'is_primary' => 0,
                        ]);
                        $sortOrder++;
                    }
                }

                // ── Sync product options + values ────────────────────────────────
                $submittedOptions = $request->input('options', []);
                $keptOptionIds    = [];

                foreach ($submittedOptions as $optData) {
                    $name          = trim($optData['name'] ?? '');
                    $valuesPayload = $optData['values'] ?? [];

                    if (!$name || empty($valuesPayload)) continue;

                    // Upsert option
                    if (!empty($optData['id'])) {
                        $opt = ProductOption::where('id', $optData['id'])->where('product_id', $product->id)->first();
                        if ($opt) { $opt->update(['name' => $name]); }
                    } else {
                        $opt = ProductOption::create(['product_id' => $product->id, 'name' => $name, 'values' => []]);
                    }
                    if (!$opt) continue;
                    $keptOptionIds[] = $opt->id;

                    // Sync option values (qty + image_path)
                    $keptValueIds = [];
                    foreach ($valuesPayload as $vData) {
                        $value     = trim($vData['value'] ?? '');
                        if (!$value) continue;
                        $qty       = max(0, (int)($vData['qty'] ?? 0));
                        $imagePath = $vData['image_path'] ?? null;

                        if (!empty($vData['id'])) {
                            $ov = ProductOptionValue::where('id', $vData['id'])
                                    ->where('product_option_id', $opt->id)->first();
                            if ($ov) {
                                $ov->qty = $qty;
                                if ($imagePath) $ov->image_path = $imagePath;
                                $ov->save();
                                $keptValueIds[] = $ov->id;
                            }
                        } else {
                            $ov = ProductOptionValue::create([
                                'product_option_id' => $opt->id,
                                'value'             => $value,
                                'qty'               => $qty,
                                'image_path'        => $imagePath,
                            ]);
                            $keptValueIds[] = $ov->id;
                        }
                    }

                    // Remove deleted values
                    ProductOptionValue::where('product_option_id', $opt->id)
                        ->whereNotIn('id', $keptValueIds)
                        ->delete();

                    // Keep values JSON in sync for backward compat
                    $opt->update(['values' => ProductOptionValue::where('product_option_id', $opt->id)
                        ->pluck('value')->toArray()]);
                }

                ProductOption::where('product_id', $product->id)
                    ->whereNotIn('id', $keptOptionIds)
                    ->delete();
                // ─────────────────────────────────────────────────────────────────
                $this->syncVariants($product);

        $baseUrl = route('admin.product.index', $product->product_type);
        $queryParams = request()->query();

        return redirect($baseUrl . '?' . http_build_query($queryParams))
            ->with('success', 'Produit mis à jour avec succès');
            }

    
        public function destroy($id)
        {
            $data = app(self::MODEL)::where('id',$id)->where('is_approved',1)->first();

            if ($data) {
                $data->visibility = !$data->visibility;
                $data->save();
                return redirect()->route(self::INDEX_ROUTE,$data->product_type)->with('success', 'Visibilité du produit modifiée avec succès');
            }

            return redirect()->route(self::INDEX_ROUTE,0)->with('error', 'Produit introuvable');
        }

        public function ApproveProduct($id)
        {
            $data = app(self::MODEL)::where('id',$id)->where('is_approved',0)->first();

            if ($data) {
                $data->is_approved = 1;
                $data->visibility = 1;
                $data->save();
                return redirect()->route('admin.product.indexApprove')->with('success', 'Produit approuvé avec succès');
            }

            return redirect()->route('admin.product.indexApprove')->with('error', 'Produit introuvable');
        }


        public function delete($id)
        {
            $data = app(self::MODEL)::find($id);

            if ($data) {
                $data->visibility = 0;
                $data->deleted = 1;
                $data->save();
                return redirect()->route(self::INDEX_ROUTE,$data->product_type)->with('success', 'Produit supprimé avec succès');
            }

            return redirect()->route(self::INDEX_ROUTE,0)->with('error', 'Produit introuvable');
        }

    // ── Variant management ────────────────────────────────────────────────────

    /** Return all variants for a product as JSON (used by the admin edit page). */
    public function getVariants($id)
    {
        $product  = app(self::MODEL)::findOrFail($id);
        $variants = ProductVariant::where('product_id', $id)->get()->map(fn($v) => [
            'id'      => $v->id,
            'options' => $v->options,
            'qty'     => $v->qty,
            'price'   => $v->price,
            'sku'     => $v->sku,
        ]);
        return response()->json(['variants' => $variants]);
    }

    /** Bulk-update qty (and optional price/sku) for variants. */
    public function updateVariants(Request $request, $id)
    {
        $request->validate(['variants' => 'required|array']);

        foreach ($request->variants as $vid => $data) {
            ProductVariant::where('id', $vid)->where('product_id', $id)->update([
                'qty'   => max(0, (int)($data['qty']   ?? 0)),
                'price' => isset($data['price']) && $data['price'] !== '' ? (float)$data['price'] : null,
                'sku'   => $data['sku'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Variantes mises à jour']);
    }

    /**
     * Auto-generate the cartesian product of all option values as variants.
     * Only runs when the product has 2+ distinct option types.
     * Preserves existing variant quantities; new combinations start at qty=0.
     */
    private function syncVariants(\App\Models\Product $product): void
    {
        $options = ProductOption::with('optionValues')->where('product_id', $product->id)->get();

        // Build option-name → [values] map, skip empty options
        $optionSets = [];
        foreach ($options as $opt) {
            $vals = $opt->optionValues->pluck('value')->filter()->values()->toArray();
            if (!empty($vals)) {
                $optionSets[$opt->name] = $vals;
            }
        }

        // Variants only make sense when 2+ option types exist.
        // Drop stale cross-option variants if we're back to a single option.
        if (count($optionSets) < 2) {
            ProductVariant::where('product_id', $product->id)->delete();
            return;
        }

        // Cartesian product
        $combinations = [[]];
        foreach ($optionSets as $optName => $values) {
            $next = [];
            foreach ($combinations as $existing) {
                foreach ($values as $value) {
                    $next[] = array_merge($existing, [$optName => $value]);
                }
            }
            $combinations = $next;
        }

        $existingVariants = ProductVariant::where('product_id', $product->id)->get();
        $keptIds          = [];

        foreach ($combinations as $combo) {
            $existing = $existingVariants->first(fn($v) => $v->matchesSelection($combo));

            if ($existing) {
                $keptIds[] = $existing->id;
            } else {
                $new = ProductVariant::create([
                    'product_id' => $product->id,
                    'options'    => $combo,
                    'qty'        => 0,
                ]);
                $keptIds[] = $new->id;
            }
        }

        // Remove variants for combinations that no longer exist
        ProductVariant::where('product_id', $product->id)
            ->whereNotIn('id', $keptIds)
            ->delete();
    }

    }
