<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminOrderController extends Controller
{
    const MODEL = Order::class;
    const INDEX_VIEW = 'admin.order.index-fr';
    const SHOW_VIEW = 'admin.order.show-fr';

    public function index(Request $request)
    {
        $merchants = Merchant::all();

        if ($request->ajax()) {
            $query = Order::query();

            // Filter by merchant if provided
            if ($request->filled('merchant_id')) {
                $query->where('merchant_id', $request->merchant_id);
            } elseif (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role_id == 2) {
                // Merchant users only see their own orders
                $query->where('merchant_id', auth()->guard('admin')->user()->merchant_id);
            }

            // Filter by status if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by payment status if provided
            if ($request->filled('is_paid')) {
                $query->where('is_paid', $request->is_paid);
            }

            // Search by order ID or customer name
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                      ->orWhere('fullname', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // Date range filter
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $query->with(['user', 'product', 'merchant', 'orderLines'])
                  ->orderByDesc('created_at');

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

        return view(self::INDEX_VIEW, compact('merchants'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'product', 'merchant', 'orderLines.product'])
                      ->findOrFail($id);

        // Check if merchant user is trying to view another merchant's order
        if (auth()->guard('admin')->check() &&
            auth()->guard('admin')->user()->role_id == 2 &&
            $order->merchant_id != auth()->guard('admin')->user()->merchant_id) {
            abort(403, 'Non autorisé');
        }

        return view(self::SHOW_VIEW, compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Check if merchant user is trying to update another merchant's order
        if (auth()->guard('admin')->check() &&
            auth()->guard('admin')->user()->role_id == 2 &&
            $order->merchant_id != auth()->guard('admin')->user()->merchant_id) {
            return response()->json(['success' => false, 'message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|integer|between:0,5',
            'admin_note' => 'nullable|string',
        ]);

        $order->status = $validated['status'];

        if (isset($validated['admin_note'])) {
            $order->admin_note = $validated['admin_note'];
        }

        // Auto-update shipping status based on order status
        if ($validated['status'] == 3) { // En livraison
            $order->is_shipped = 1;
        } elseif ($validated['status'] == 4) { // Livrée
            $order->is_shipped = 1;
        }

        $order->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut de la commande mis à jour avec succès',
                'status_text' => $order->status_text
            ]);
        }

        return redirect()->route('admin.orders.show', $id)
                        ->with('success', 'Statut de la commande mis à jour avec succès');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Check if merchant user is trying to update another merchant's order
        if (auth()->guard('admin')->check() &&
            auth()->guard('admin')->user()->role_id == 2 &&
            $order->merchant_id != auth()->guard('admin')->user()->merchant_id) {
            return response()->json(['success' => false, 'message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'is_paid' => 'required|boolean',
            'payment_note' => 'nullable|string',
        ]);

        $order->is_paid = $validated['is_paid'];

        if (isset($validated['payment_note'])) {
            $order->payment_note = $validated['payment_note'];
        }

        $order->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut de paiement mis à jour avec succès',
                'payment_status_text' => $order->payment_status_text
            ]);
        }

        return redirect()->route('admin.orders.show', $id)
                        ->with('success', 'Statut de paiement mis à jour avec succès');
    }
}
