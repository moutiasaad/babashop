<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Merchant;
use App\Models\MerchantCategorie;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminMerchantController extends Controller
{
    const INDEX_ROUTE = 'admin.merchant.index';
    const ADD_ROUTE = 'admin.merchant.add';
    const STORE_ROUTE = 'admin.merchant.store';
    const EDIT_ROUTE = 'admin.merchant.edit';
    const UPDATE_ROUTE = 'admin.merchant.update';
    const DESTROY_ROUTE = 'admin.merchant.destroy';
    const INDEX_VIEW = 'admin.merchant.index-fr';
    const ADD_VIEW = 'admin.merchant.add-fr';
    const EDIT_VIEW = 'admin.merchant.edit-fr';
    const MODEL = Merchant::class;

    public function index(Request $request)
    {
        $query = self::MODEL::query();

        // Filter by visibility if requested
        if ($request->has('hidden')) {
            $query->where('visibility', 0);
        } else {
            $query->where('deleted', 0);
        }

        // Order by order_item
        $query->orderBy('order_item');

        // Handle search
        if ($request->has('search')) {
            $query->where('brand_name', 'like', '%' . $request->input('search') . '%');
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
        $merchantCategories = MerchantCategorie::all();
        return view(self::ADD_VIEW, compact('merchantCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'type_merchant_id' => 'required|integer',
            'street_name' => 'required|string|max:255',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'open_at' => 'required|date_format:H:i',
            'close_at' => 'required|date_format:H:i',
            'notif_email' => 'nullable|email',
            'whatsapp_number' => 'nullable|string|max:50',
            'delivery_is_free' => 'nullable|boolean',
            'return_policy' => 'nullable|string',
            'order_item' => 'nullable|integer|min:1',
            'categories' => 'nullable|array',
            // Merchant user validation
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:admins,email',
            'user_password' => 'required|string|min:6',
            'user_phone' => 'nullable|string|max:50',
        ], [
            'brand_name.required' => 'Le nom de la marque est requis.',
            'description.required' => 'La description est requise.',
            'image.required' => 'L\'image est requise.',
            'type_merchant_id.required' => 'Le type de boutique est requis.',
            'street_name.required' => 'Le nom de la rue est requis.',
            'open_at.required' => 'L\'heure d\'ouverture est requise.',
            'close_at.required' => 'L\'heure de fermeture est requise.',
            'user_name.required' => 'Le nom du responsable est requis.',
            'user_email.required' => 'L\'email de connexion est requis.',
            'user_email.unique' => 'Cet email est déjà utilisé.',
            'user_password.required' => 'Le mot de passe est requis.',
            'user_password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $fileExtension = strtolower($request->file('image')->getClientOriginalExtension());
            $fileName = time() . '.' . $fileExtension;
            $request->file('image')->move(public_path("uploads/merchants"), $fileName);
            $validated['image'] = "/uploads/merchants/" . $fileName;
        }

        // Handle ordering
        $orderItem = $validated['order_item'] ?? null;
        if ($orderItem) {
            self::MODEL::where('order_item', '>=', $orderItem)->increment('order_item');
        } else {
            $orderItem = self::MODEL::max('order_item') + 1;
        }

        // Create merchant
        $merchant = self::MODEL::create([
            'brand_name' => $validated['brand_name'],
            'description' => $validated['description'],
            'image' => $validated['image'],
            'type_merchant_id' => $validated['type_merchant_id'],
            'street_name' => $validated['street_name'],
            'latitude' => $validated['latitude'] ?? '0',
            'longitude' => $validated['longitude'] ?? '0',
            'open_at' => $validated['open_at'],
            'close_at' => $validated['close_at'],
            'notif_email' => $validated['notif_email'],
            'whatsapp_number' => $validated['whatsapp_number'],
            'return_policy' => $validated['return_policy'],
            'order_item' => $orderItem,
            'categories' => $validated['categories'] ?? [],
            'visibility' => 1,
            'deleted' => 0,
        ]);

        // Create merchant user account linked to the boutique
        Admin::create([
            'name' => $validated['user_name'],
            'email' => $validated['user_email'],
            'password' => Hash::make($validated['user_password']),
            'phone' => $validated['user_phone'],
            'role_id' => 2, // Merchant user role
            'merchant_id' => $merchant->id,
            'title' => 'Responsable Boutique',
        ]);

        return redirect()->route(self::INDEX_ROUTE)->with('success', 'Boutique et compte utilisateur ajoutés avec succès.');
    }

    public function showEdit($id)
    {
        $merchant = self::MODEL::findOrFail($id);
        $merchantCategories = MerchantCategorie::all();
        $merchantUser = Admin::where('merchant_id', $id)->first();
        return view(self::EDIT_VIEW, compact('merchant', 'merchantCategories', 'merchantUser'));
    }

    public function update(Request $request, $id)
    {
        $merchant = self::MODEL::findOrFail($id);
        $merchantUser = Admin::where('merchant_id', $id)->first();

        // Build validation rules dynamically based on whether user exists
        $userValidationRules = [
            'user_name' => 'nullable|string|max:255',
            'user_phone' => 'nullable|string|max:50',
        ];

        if ($merchantUser) {
            // Existing user - email must be unique except for this user
            $userValidationRules['user_email'] = 'nullable|email|unique:admins,email,' . $merchantUser->id;
            $userValidationRules['user_password'] = 'nullable|string|min:6';
        } else {
            // New user - all fields required if any user field is filled
            if ($request->filled('user_email') || $request->filled('user_name')) {
                $userValidationRules['user_name'] = 'required|string|max:255';
                $userValidationRules['user_email'] = 'required|email|unique:admins,email';
                $userValidationRules['user_password'] = 'required|string|min:6';
            }
        }

        $validated = $request->validate(array_merge([
            'brand_name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'type_merchant_id' => 'required|integer',
            'street_name' => 'required|string|max:255',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'notif_email' => 'nullable|email',
            'whatsapp_number' => 'nullable|string|max:50',
            'return_policy' => 'nullable|string',
            'order_item' => 'nullable|integer|min:1',
            'categories' => 'nullable|array',
        ], $userValidationRules), [
            'brand_name.required' => 'Le nom de la marque est requis.',
            'description.required' => 'La description est requise.',
            'type_merchant_id.required' => 'Le type de boutique est requis.',
            'street_name.required' => 'Le nom de la rue est requis.',
            'user_name.required' => 'Le nom du responsable est requis.',
            'user_email.required' => 'L\'email de connexion est requis.',
            'user_email.unique' => 'Cet email est déjà utilisé.',
            'user_password.required' => 'Le mot de passe est requis.',
            'user_password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $fileExtension = strtolower($request->file('image')->getClientOriginalExtension());
            $fileName = time() . '.' . $fileExtension;
            $request->file('image')->move(public_path("uploads/merchants"), $fileName);
            $validated['image'] = "/uploads/merchants/" . $fileName;
        }

        // Update merchant (exclude user fields)
        $merchantData = collect($validated)->except(['user_name', 'user_email', 'user_password', 'user_phone'])->toArray();
        $merchant->update($merchantData);

        // Handle merchant user update or creation
        if ($merchantUser) {
            // Update existing user
            $userData = [
                'name' => $validated['user_name'] ?? $merchantUser->name,
                'email' => $validated['user_email'] ?? $merchantUser->email,
                'phone' => $validated['user_phone'] ?? $merchantUser->phone,
            ];
            if (!empty($validated['user_password'])) {
                $userData['password'] = Hash::make($validated['user_password']);
            }
            $merchantUser->update($userData);
        } elseif ($request->filled('user_email') && $request->filled('user_name')) {
            // Create new user if fields are provided
            Admin::create([
                'name' => $validated['user_name'],
                'email' => $validated['user_email'],
                'password' => Hash::make($validated['user_password']),
                'phone' => $validated['user_phone'] ?? null,
                'role_id' => 1,
                'merchant_id' => $merchant->id,
                'title' => 'Responsable Boutique',
            ]);
        }

        return redirect()->route(self::INDEX_ROUTE)->with('success', 'Boutique mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $merchant = self::MODEL::find($id);

        if ($merchant) {
            $merchant->visibility = 0;
            $merchant->deleted = 1;
            $merchant->save();
            return redirect()->route(self::INDEX_ROUTE)->with('success', 'Boutique supprimée avec succès.');
        }

        return redirect()->route(self::INDEX_ROUTE)->with('error', 'Boutique introuvable.');
    }

    public function visibility($id)
    {
        $merchant = self::MODEL::find($id);

        if ($merchant) {
            $merchant->visibility = !$merchant->visibility;
            $merchant->save();
            return redirect()->route(self::INDEX_ROUTE)->with('success', 'Visibilité de la boutique modifiée avec succès.');
        }

        return redirect()->route(self::INDEX_ROUTE)->with('error', 'Boutique introuvable.');
    }
}
