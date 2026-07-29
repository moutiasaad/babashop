<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Orders;
use App\Models\Order_lines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuickOrderController extends Controller
{
    /**
     * Store a quick order.
     */
    public function store(Request $request)
    {
        // Check if quick order is enabled
        if (!env('QUICK_ORDER_ENABLED', false)) {
            return response()->json([
                'success' => false,
                'message' => 'Les commandes rapides ne sont pas disponibles pour le moment.'
            ], 403);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:product,id',
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:1000',
        ], [
            'product_id.required' => 'Le produit est requis.',
            'product_id.exists' => 'Le produit n\'existe pas.',
            'fullname.required' => 'Le nom complet est requis.',
            'phone.required' => 'Le numero de telephone est requis.',
            'address.required' => 'L\'adresse est requise.',
            'quantity.required' => 'La quantite est requise.',
            'quantity.min' => 'La quantite doit etre au moins 1.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Get the product
        $product = Product::where('visibility', 1)
            ->where('deleted', 0)
            ->where('is_approved', 1)
            ->find($request->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est plus disponible.'
            ], 404);
        }

        // Check stock if applicable
        if ($product->qty !== null && $product->qty < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuffisant. Disponible: ' . $product->qty
            ], 400);
        }

        // Calculate price
        $unitPrice = ($product->discount_price && $product->discount_price < $product->price)
            ? $product->discount_price
            : $product->price;
        $totalPrice = $unitPrice * $request->quantity;

        DB::beginTransaction();
        try {
            // Create the order using DB insert to avoid accessor issues
            // Use user_id = 99999 for guest orders (Guest User)
            $guestUserId = 645;

            $orderId = DB::table('orders')->insertGetId([
                'user_id' => $guestUserId,
                'product_id' => $product->id,
                'merchant_id' => $product->merchant_id,
                'fullname' => $request->fullname,
                'phone' => $request->phone,
                'address' => $request->address,
                'total_price' => $totalPrice,
                'delivery_cost' => 0,
                'total_net_a_pay' => $totalPrice,
                'status' => 0,
                'is_paid' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create order line
            DB::table('order_lines')->insert([
                'order_id' => $orderId,
                'product_id' => $product->id,
                'user_id' => $guestUserId,
                'unit_price' => $unitPrice,
                'price' => $totalPrice,
                'qty' => $request->quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Decrease product stock if applicable
            if ($product->qty !== null) {
                $product->decrement('qty', $request->quantity);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Commande creee avec succes!',
                'order_id' => $orderId
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quick order error: ' . $e->getMessage() . ' - Line: ' . $e->getLine() . ' - File: ' . $e->getFile());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
}
