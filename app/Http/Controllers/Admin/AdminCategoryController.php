<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    const INDEX_ROUTE = 'admin.category.index';
    const ADD_ROUTE = 'admin.category.add';
    const STORE_ROUTE = 'admin.category.store';
    const DESTROY_ROUTE = 'admin.category.destroy';
    const INDEX_VIEW = 'admin.category.index-fr';
    const ADD_VIEW = 'admin.category.add-fr';
    const EDIT_VIEW = 'admin.category.edit-fr';
    const MODEL = \App\Models\Category::class;
    const MODEL_NAME = 'Category';
    const MODEL_NAME_PLURAL = 'categories';


    public function index(Request $request)
    {
        $query = app(self::MODEL)::query();

        // Filter out deleted categories
        $query->where('deleted', 0);

        // Order by order_item
        $query->orderBy('order_item');
    
        // Handle search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }
    
        // Pagination
        $items = $query->paginate($request->get('per_page', 10)); // Default to 10 per page
         
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
    public function showEdit($id)
    {
        $category = app(self::MODEL)::findOrFail($id);
        return view(self::EDIT_VIEW, compact('category'));
    }
    
    public function update(Request $request, $id)
    {
        $category = app(self::MODEL)::findOrFail($id);

        // Validate the form input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'order_item' => 'nullable|integer|min:1',
            'subcategories' => 'nullable|array',
            'subcategories.*' => 'exists:category,id',
        ], [
            'name.required' => 'Le nom est requis.',
            'name.string' => 'Le nom doit être un texte.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'description.string' => 'La description doit être un texte.',
            'order_item.integer' => 'L\'ordre doit être un nombre entier.',
            'order_item.min' => 'L\'ordre doit être 1 ou plus.',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $fileExtension = strtolower($request->file('image')->getClientOriginalExtension());
            $fileName = time() . '.' . $fileExtension;
            $request->file('image')->move(public_path("uploads/categories"), $fileName);
            $validated['image'] = "/uploads/categories/" . $fileName;
        }

        // Handle dynamic reordering
        if (isset($validated['order_item'])) {
            $newOrder = $validated['order_item'];

            // Remove the current category's order temporarily
            app(self::MODEL)::where('order_item', '>', $category->order_item)
                ->decrement('order_item');

            // Reassign orders dynamically based on the new position
            app(self::MODEL)::where('order_item', '>=', $newOrder)
                ->increment('order_item');

            $category->order_item = $newOrder;
        }

        // Update the category
        $category->update($validated);

        // Sync subcategories - will add new, remove old, and keep existing
        $category->subcategories()->sync($validated['subcategories'] ?? []);

        // Reorganize all categories to ensure proper sequential ordering
        $categories = app(self::MODEL)::orderBy('order_item')->get();
        $order = 1;
        foreach ($categories as $cat) {
            $cat->update(['order_item' => $order]);
            $order++;
        }

        return redirect()->route(self::INDEX_ROUTE)->with('success', 'Catégorie mise à jour avec succès.');
    }
    
        
    public function store(Request $request)
    {
        // Validate the form input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'order_item' => 'nullable|integer|min:1',
            'subcategories' => 'nullable|array',
            'subcategories.*' => 'exists:category,id',
        ], [
            'name.required' => 'Le nom est requis.',
            'name.string' => 'Le nom doit être un texte.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'description.string' => 'La description doit être un texte.',
            'image.required' => 'L\'image est requise.',
            'order_item.integer' => 'L\'ordre doit être un nombre entier.',
            'order_item.min' => 'L\'ordre doit être 1 ou plus.',
        ]);

        // Handle image upload
        $fileExtension = strtolower($request->file('image')->getClientOriginalExtension());
        $fileName = time() . '.' . $fileExtension;
        $request->file('image')->move(public_path("uploads/categories"), $fileName);
        $imagePath = "/uploads/categories/" . $fileName;

        // Handle dynamic ordering
        $orderItem = $validated['order_item'] ?? null;

        if ($orderItem) {
            // If order_item is provided, reorder existing categories
            app(self::MODEL)::where('order_item', '>=', $orderItem)->increment('order_item');
        } else {
            // If no order_item is provided, assign the next available order
            $orderItem = app(self::MODEL)::max('order_item') + 1;
        }

        // Create new category record
        $newCategory = app(self::MODEL)::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'order_item' => $orderItem,
            'visibility' => 1,
            'deleted' => 0,
        ]);

        // Attach subcategories if provided
        if (!empty($validated['subcategories'])) {
            $newCategory->subcategories()->attach($validated['subcategories']);
        }

        // Reorganize all categories to ensure proper sequential ordering
        $categories = app(self::MODEL)::orderBy('order_item')->get();
        $order = 1;
        foreach ($categories as $cat) {
            $cat->update(['order_item' => $order]);
            $order++;
        }

        // Redirect back with success message
        return redirect()->route(self::INDEX_ROUTE)->with('success', 'Catégorie ajoutée avec succès.');
    }
    
    public function visiblity($id)
    {
        $data = app(self::MODEL)::where('id',$id)->first();
    
        if ($data) {
            $data->visibility = !$data->visibility;
            $data->save();
            return redirect()->route(self::INDEX_ROUTE)->with('success', 'Visibilité de la catégorie modifiée avec succès.');
        }

        return redirect()->route(self::INDEX_ROUTE)->with('error', 'Catégorie introuvable.');
    }

    public function destroy($id)
    {
        $data = app(self::MODEL)::find($id);
    
        if ($data) {
            $data->visibility = 0;
            $data->deleted = 1;
            $data->save();
            return redirect()->route(self::INDEX_ROUTE)->with('success', 'Catégorie supprimée avec succès.');
        }

        return redirect()->route(self::INDEX_ROUTE)->with('error', 'Catégorie introuvable.');
    }
    }
