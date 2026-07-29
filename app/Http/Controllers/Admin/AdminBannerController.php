<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminBannerController extends Controller
{
    const INDEX_ROUTE = 'admin.banner.index';
    const ADD_ROUTE = 'admin.banner.add';
    const STORE_ROUTE = 'admin.banner.store';
    const EDIT_ROUTE = 'admin.banner.edit';
    const UPDATE_ROUTE = 'admin.banner.update';
    const DESTROY_ROUTE = 'admin.banner.destroy';
    const INDEX_VIEW = 'admin.banner.index-fr';
    const ADD_VIEW = 'admin.banner.add-fr';
    const EDIT_VIEW = 'admin.banner.edit-fr';
    const MODEL = Banner::class;

    public function index(Request $request)
    {
        $query = self::MODEL::query();

        // Filter out deleted banners
        $query->where('deleted', 0);

        // Order by order_item
        $query->orderBy('order_item');

        // Handle search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Pagination
        $items = $query->paginate($request->get('per_page', 10));

        if ($request->ajax()) {
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

        return view(self::INDEX_VIEW, compact('items'));
    }

    public function showAdd()
    {
        return view(self::ADD_VIEW);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'order_item' => 'nullable|integer|min:1',
        ], [
            'name.required' => 'Le nom est requis.',
            'name.string' => 'Le nom doit être un texte.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'image.required' => 'L\'image est requise.',
            'order_item.integer' => 'L\'ordre doit être un nombre entier.',
            'order_item.min' => 'L\'ordre doit être 1 ou plus.',
        ]);

        // Handle image upload
        $fileExtension = strtolower($request->file('image')->getClientOriginalExtension());
        $fileName = time() . '.' . $fileExtension;
        $request->file('image')->move(public_path("uploads/banners"), $fileName);
        $imagePath = "/uploads/banners/" . $fileName;

        // Handle dynamic ordering
        $orderItem = $validated['order_item'] ?? null;

        if ($orderItem) {
            self::MODEL::where('order_item', '>=', $orderItem)->increment('order_item');
        } else {
            $orderItem = self::MODEL::max('order_item') + 1;
        }

        // Create new banner record
        self::MODEL::create([
            'name' => $validated['name'],
            'image' => $imagePath,
            'link' => $validated['link'] ?? null,
            'order_item' => $orderItem,
            'visibility' => 1,
            'deleted' => 0,
        ]);

        return redirect()->route(self::INDEX_ROUTE)->with('success', 'Bannière ajoutée avec succès.');
    }

    public function showEdit($id)
    {
        $banner = self::MODEL::findOrFail($id);
        return view(self::EDIT_VIEW, compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = self::MODEL::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'order_item' => 'nullable|integer|min:1',
        ], [
            'name.required' => 'Le nom est requis.',
            'name.string' => 'Le nom doit être un texte.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'order_item.integer' => 'L\'ordre doit être un nombre entier.',
            'order_item.min' => 'L\'ordre doit être 1 ou plus.',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $fileExtension = strtolower($request->file('image')->getClientOriginalExtension());
            $fileName = time() . '.' . $fileExtension;
            $request->file('image')->move(public_path("uploads/banners"), $fileName);
            $validated['image'] = "/uploads/banners/" . $fileName;
        }

        // Handle dynamic reordering
        if (isset($validated['order_item'])) {
            $newOrder = $validated['order_item'];

            // Remove the current banner's order temporarily
            self::MODEL::where('order_item', '>', $banner->order_item)
                ->decrement('order_item');

            // Reassign orders dynamically based on the new position
            self::MODEL::where('order_item', '>=', $newOrder)
                ->increment('order_item');

            $banner->order_item = $newOrder;
        }

        // Update the banner
        $banner->update($validated);

        // Reorganize all banners to ensure proper sequential ordering
        $banners = self::MODEL::orderBy('order_item')->get();
        $order = 1;
        foreach ($banners as $b) {
            $b->update(['order_item' => $order]);
            $order++;
        }

        return redirect()->route(self::INDEX_ROUTE)->with('success', 'Bannière mise à jour avec succès.');
    }

    public function visibility($id)
    {
        $data = self::MODEL::where('id', $id)->first();

        if ($data) {
            $data->visibility = !$data->visibility;
            $data->save();
            return redirect()->route(self::INDEX_ROUTE)->with('success', 'Visibilité de la bannière modifiée avec succès.');
        }

        return redirect()->route(self::INDEX_ROUTE)->with('error', 'Bannière introuvable.');
    }

    public function destroy($id)
    {
        $data = self::MODEL::find($id);

        if ($data) {
            $data->visibility = 0;
            $data->deleted = 1;
            $data->save();
            return redirect()->route(self::INDEX_ROUTE)->with('success', 'Bannière supprimée avec succès.');
        }

        return redirect()->route(self::INDEX_ROUTE)->with('error', 'Bannière introuvable.');
    }
}
