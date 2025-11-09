<?php

namespace App\Http\Controllers\Api;

use App\Models\Carts;
use App\Models\Coupon;
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
// In your controller
    public function store(Request $request)
    {
        $request->validate([
            'cart'     => 'required|array',
            'address'  => 'required|string',
            'phone'    => 'required|string',
            'fullname' => 'required|string',
        ]);

        $carts         = Carts::whereIn('id', $request->cart)->get();
        $coupon        = $this->getValidCoupon($request->coupon);

        $delivery_cost = config('company.delivery_cost');

        $order = Orders::create([
            'user_id'             => auth()->id(),
            'address'             => $request->address,
            'order_type'             => $request->order_type,
            'latitude'            => $request->latitude  ?? 24.71,
            'longitude'           => $request->longitude ?? 46.70,
            'phone'               => $request->phone,
            'fullname'            => $request->fullname,
            'merchant_id'         => $carts->first()->product->merchant_id,
            'delivery_cost'       => $delivery_cost,
            'is_rated'            => 2,
            'status'              => 1,
            'is_paid'             => 0,
            'start_date_delivery' => $request->start_date_delivery ?? $carts->first()->preferred_delivery_start,
            'end_date_delivery'   => $request->end_date_delivery   ?? $carts->first()->preferred_delivery_end,
            'order_version'           => 3,
            'client_note'         => $request->client_note,
            'hide_buyer_identity' => $request->hide_buyer_identity ?? 0,
            'designAttributeIds'  => $request->designAttributeIds ? json_encode($request->designAttributeIds) : null,
            'designOptionIds'     => $request->designOptionIds  ? json_encode($request->designOptionIds)  : null,
            'card_description'    => $carts->first()->card_description   ? json_encode($request->card_description)   : null,
        ]);
        $total_price_order=0;


        foreach ($carts as $cart) {
            $price          = $this->calculatePrice($cart->product, $coupon);
            $discount_price = $coupon ? ($this->calculatePrice($cart->product, '') - $price) : 0;
            $total_price_order += ($price ?? $discount_price ) * $cart->qte;
            Order_lines::create([
                'user_id'        => auth()->id(),
                'order_id'       => $order->id,
                'product_id'     => $cart->product_id,
                'unit_price'     => $price,
                'price' =>  $price ?? $discount_price,
                'qty'            => $cart->qte,
                'card_description'  => $cart->card_description ?? null,
            ]);
        }

        if ($coupon) {
            $order->coupon_id = $coupon->id;
        
            if ($coupon->discount_type === 'percent') {
                $discount = ($total_price_order * $coupon->discount) / 100;
                $total_price_order -= $discount;
            } elseif ($coupon->discount_type === 'fixed') {
                $total_price_order -= $coupon->discount;
            }
        
            // Optional: prevent negative total
            if ($total_price_order < 0) {
                $total_price_order = 0;
            }
        }
        

        $is_free_delivery = false;
        $merchant = Merchant::where("id",$carts->first()->product->merchant_id)->first();
        $merchantFreeDelivery = $merchant->price_free_delivery;
        if($merchantFreeDelivery > 0 ){
            $is_free_delivery = true;
        }

        if($total_price_order > $merchantFreeDelivery && $is_free_delivery){
            $delivery_cost = 0 ;
            $order->delivery_cost = $delivery_cost;

        }
        
        $order->total_price    = $total_price_order;
        $order->total_net_a_pay = $total_price_order + $delivery_cost;
        $order->save();

        Carts::whereIn('id', $request->cart)->delete();

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
        $orders = Orders::with(['order_lines.product'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($orders);
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
        
        $order = Orders::where('id',$id)->with('product')->with('client')->with('merchant');
        return response()->json($order);
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
