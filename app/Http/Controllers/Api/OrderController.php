<?php

namespace App\Http\Controllers\Api;

use App\Models\Carts;
use App\Models\Coupon;
use App\Models\DeliveryZone;
use App\Models\Orders;
use App\Models\Merchant;
use App\Models\Order_lines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\OrderNotificationEmail;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cart'              => 'required|array',
            'address'           => 'required|string',
            'phone'             => 'required|string',
            'fullname'          => 'required|string',
            'delivery_zone_id'  => 'nullable|exists:delivery_zones,id',
            'payment_method'    => 'nullable|in:cod,online',
        ]);
        $paymentMethod = $request->input('payment_method', Orders::PAYMENT_METHOD_COD);

        $carts = Carts::whereIn('id', $request->cart)
            ->with('product')
            ->get();

        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Cart empty'], 400);
        }

        // Delivery fee: prefer the per-governorate rate when a zone is supplied,
        // otherwise fall back to the global company.delivery_cost config value.
        $zone = null;
        if ($request->filled('delivery_zone_id')) {
            $zone = DeliveryZone::where('is_active', true)->find($request->delivery_zone_id);
        }
        $delivery_cost = $zone
            ? (float) $zone->delivery_fee
            : ((float) (config('company.delivery_cost') ?? 0));

        $order = DB::transaction(function () use ($request, $carts, $delivery_cost, $zone, $paymentMethod) {
            $order = Orders::create([
                'user_id'               => auth()->id(),
                'address'               => $request->address,
                'order_type'            => $request->order_type,
                'latitude'              => $request->latitude  ?? 24.71,
                'longitude'             => $request->longitude ?? 46.70,
                'phone'                 => $request->phone,
                'fullname'              => $request->fullname,
                'merchant_id'           => $carts->first()->product->merchant_id,
                'delivery_zone_id'      => $zone?->id,
                'delivery_cost'         => $delivery_cost,
                'delivery_fee_snapshot' => $delivery_cost,
                'is_rated'              => 2,
                'status'                => 0,
                'is_paid'               => 0,
                'payment_method'        => $paymentMethod,
                'start_date_delivery'   => $request->start_date_delivery ?? $carts->first()->preferred_delivery_start,
                'end_date_delivery'     => $request->end_date_delivery   ?? $carts->first()->preferred_delivery_end,
                'order_version'         => 3,
                'client_note'           => $request->client_note,
                'hide_buyer_identity'   => $request->hide_buyer_identity ?? 0,
                'designAttributeIds'    => $request->designAttributeIds ? json_encode($request->designAttributeIds) : null,
                'designOptionIds'       => $request->designOptionIds  ? json_encode($request->designOptionIds)  : null,
                'card_description'      => $carts->first()->card_description ? json_encode($carts->first()->card_description) : null,
            ]);

            $total_price_order = 0;

            foreach ($carts as $cart) {
                $price = $cart->product->discount_price && (float)$cart->product->discount_price > 0
                    ? (float)$cart->product->discount_price
                    : (float)$cart->product->price;

                $qty = max(1, (int)($cart->qte ?? 1));
                $line_total = $price * $qty;
                $total_price_order += $line_total;

                Order_lines::create([
                    'user_id'          => auth()->id(),
                    'order_id'         => $order->id,
                    'product_id'       => $cart->product_id,
                    'unit_price'       => $price,
                    'price'            => $line_total,
                    'qty'              => $qty,
                    'card_description' => $cart->card_description ?? null,
                    'selected_options' => $cart->selected_options ?? null,
                ]);

                // Decrement product stock with lock to prevent overselling
                \App\Models\Product::where('id', $cart->product_id)
                    ->where('qty', '>=', $qty)
                    ->lockForUpdate()
                    ->decrement('qty', $qty);
            }

            // Free delivery: applied when either the merchant or the zone threshold is met.
            $merchant = Merchant::find($order->merchant_id);
            $merchantFree = $merchant && $merchant->price_free_delivery > 0
                && $total_price_order > $merchant->price_free_delivery;
            $zoneFree = $zone && $zone->free_shipping_threshold !== null
                && $total_price_order >= (float) $zone->free_shipping_threshold;
            if ($merchantFree || $zoneFree) {
                $order->delivery_cost = 0;
                $order->delivery_fee_snapshot = 0;
            }

            $order->total_price     = $total_price_order;
            $order->total_net_a_pay = $total_price_order + $delivery_cost;
            $order->save();

            Carts::whereIn('id', $request->cart)->delete();

            return $order;
        });

        return response()->json([
            'message' => 'Order created successfully',
            'order'   => $order->load('order_lines.product'),
        ]);
    }
        
    public function createRating(Request $request){
        
        $userId = auth()->id();
        $star = $request->input('star');
        $star_2 = $request->input('star_2');
        $description = $request->input('description');
        $orderId = $request->input('order_id');
        
        $order = Orders::where('id',$orderId)->first();
        $order->is_rated = 2;
        $order->save();

        $user= auth()->user();
        $user->order_rate = null;
        $user->save();
        
        $marchantId = $order->merchant_id;
         $ratingId = DB::table('ratings')->insertGetId([
            'user_id' => $userId,
            'star' => $star,
            'star_2' => $star_2,
            'description' => $description,
            'order_id' => $orderId,
            'marchant_id' => $marchantId,
        ]);

        return response()->json([
            'message' => 'Rating created successfully',
            'rating_id' => $ratingId
        ], 201);
    }
    
    
    public function orderUpdate(Request $request){
        $order = Orders::find($request->order_id);
        $order->payment_note = $request->payment_note ?? null;
        $order->save();
    return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * Mark a COD order as paid. Called by driver/admin when cash is collected
     * on delivery. No-op if the order is already paid or wasn't a COD order.
     */
    public function markPaid(Request $request, Orders $order)
    {
        if (!$order->isCod()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande n\'est pas en paiement à la livraison.',
            ], 422);
        }

        if ((int) $order->is_paid === 1) {
            return response()->json([
                'success' => true,
                'message' => 'Commande déjà marquée comme payée.',
                'order'   => $order,
            ]);
        }

        $order->is_paid = 1;
        $order->paid_at = now();
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Paiement enregistré.',
            'order'   => $order,
        ]);
    }

public function validateCouponOnOrder(Request $request)
{
    $orderId    = $request->input('order_id');
    $couponCode = $request->input('coupon');

    // Reset coupon if both order_id and coupon are empty
    if (empty($orderId) || empty($couponCode)) {
        if ($orderId) {
            $order = Orders::find($orderId);
            if ($order) {
                $beforePrice = 0;
                foreach ($order->order_lines as $line) {
                    $beforePrice += ($line->product->discount_price > 0 ? $line->product->discount_price : $line->product->price) * $line->qty;
                }

                $order->coupon_id       = null;
                $order->total_price     = $beforePrice;
                $order->total_net_a_pay = $beforePrice + $order->delivery_cost;
                $order->save();

                return response()->json([
                    'message'      => 'Coupon removed successfully',
                    'before_price' => $beforePrice,
                    'new_price'    => $beforePrice,
                    'order'        => $order
                ]);
            }
        }

        return response()->json(['error' => 'Order not found or no order ID provided'], 404);
    }

    $order = Orders::with('order_lines.product')->find($orderId);
    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    if ($order->getRawOriginal('status') !== 1) {
        return response()->json([
            'error'    => 'Order is not in waiting payment status',
            'original' => $order->getRawOriginal('status') // raw integer from DB
        ], 404);
    }

    $coupon = $this->getValidCoupon($couponCode);
    if (!$coupon) {
        return response()->json(['error' => 'Invalid or inactive coupon'], 400);
    }

    $beforePrice = 0;
    foreach ($order->order_lines as $line) {
        $beforePrice += ($line->product->discount_price > 0 ? $line->product->discount_price : $line->product->price) * $line->qty;
    }

    // Apply discount
    $discount = 0;
    if ($coupon->discount_type === 'percent') {
        $discount = ($beforePrice * $coupon->discount) / 100;
    } elseif ($coupon->discount_type === 'fixed') {
        $discount = $coupon->discount;
    }

    $newPrice = max($beforePrice - $discount, 0);

    // Update order
    $order->coupon_id       = $coupon->id;
    $order->total_price     = $newPrice;
    $order->total_net_a_pay = $newPrice + $order->delivery_cost;
    $order->save();

    return response()->json([
        'message'      => 'Coupon applied successfully',
        'coupon'       => $coupon->code,
        'before_price' => $beforePrice,
        'discount'     => $discount,
        'new_price'    => $newPrice,
        'order'        => $order
    ]);
}
    public function index()
    {
        $userId = auth()->id();

        if (!$userId) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $orders = Orders::with(['order_lines.product'])
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($orders->map(fn($order) => array_merge(
            $order->toArray(),
            ['status' => $order->getRawOriginal('status')]
        )));
    }

    public function statuses()
    {
        return response()->json([
            ['value' => 0, 'label' => 'En attente',    'color' => '#6C757D'],
            ['value' => 1, 'label' => 'Confirmée',      'color' => '#17A2B8'],
            ['value' => 2, 'label' => 'En préparation', 'color' => '#0275D8'],
            ['value' => 3, 'label' => 'En livraison',   'color' => '#FF9800'],
            ['value' => 4, 'label' => 'Livrée',         'color' => '#28A745'],
            ['value' => 5, 'label' => 'Annulée',        'color' => '#DC3545'],
        ]);
    }


    // public function index(Request $request)
    // {
    //     $orderStatus = (int) $request->query('order_status');
    
    //     $query = Orders::where('user_id', auth()->id())
    //                    ->with(['product', 'client', 'driver', 'merchant']);
    
    //     if ($orderStatus === 2) {
    //         // when ?order_status=2 → only statuses 3, 4 & 5
    //         $query->whereIn('status', [3, 4, 5]);
    //     }
    //     elseif ($orderStatus === 1) {
    //         // when ?order_status=1 → statuses >1 except 3,4,5
    //         $query->where('status', '>', 1)
    //               ->whereNotIn('status', [3, 4, 5]);
    //     }
    //     else {
    //         // default (no order_status or other values) → statuses >1
    //         $query->where('status', '>', 1);
    //     }
    
    //     $orders = $query->orderBy('id', 'desc')->get();
    
    //     return response()->json($orders);
    // }

    public function show($id){
        // Bug in older code: the ->with(...) chain was never terminated with
        // a fetch, so `$order` was a query builder and the response was an
        // opaque builder-object with no useful fields.
        $order = Orders::with([
                'product',
                'client',
                'merchant',
                'deliveryZone',
                'order_lines.product',
            ])
            ->find($id);

        if (!$order) {
            return response()->json(['message' => 'Commande introuvable.'], 404);
        }

        return response()->json(['order' => $order]);
    }

    public function getOrderStatus($id){
        $order = Orders::find($id);
        return response()->json(['status' => $order->status , 'price' => $order->total_net_a_pay]);
    }
    private function getValidCoupon($couponCode)
    {
        if (!$couponCode) {
            return null;
        }

        return Coupon::where('code', $couponCode)
            ->where('active', 1)
            ->first();
    }

    private function calculatePrice($product, $coupon)
    {
        $price = $product->discount_price > 0 ? $product->discount_price : $product->price;

        if ($coupon) {
            if ($coupon->discount_type == 'fixed') {
                $price -= $coupon->discount;
            } elseif ($coupon->discount_type == 'percent') {
                $price -= ($price * $coupon->discount / 100);
            }
        }

    return max($price, 0);
    }

    public function getPriceByCoupon ($couponCode , $cart){
        $coupon = $this->getValidCoupon($couponCode);
        if(!$coupon){
return response()->json(['error' => 'coupon no valid'], 400);
        }
        //dd($cart);
        $carts = Carts::where('id', $cart)->get();

        $price = 0;
        foreach ($carts as $cart){
            $price += $this->calculatePrice($cart->product, $coupon);
        }
        Log::info($couponCode."  ".$price);

        return response()->json(['price' => $price]);
    }
}
