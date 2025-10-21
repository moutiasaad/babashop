<?php

namespace App\Http\Controllers\Api;

use App\Models\Carts;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\NotificationTemplate;

class CartController extends Controller
{
    // Add item to cart
    public function notifyAbandonedCarts()
    {
        $now = Carbon::now();

        // Get latest cart per user
        $latestCarts = Carts::select(DB::raw('MAX(id) as id'))
            ->groupBy('user_id')
            ->get()
            ->pluck('id');

        // Retrieve cart data with user (only users with fcm_token)
        $carts = Carts::with('user')
            ->whereIn('id', $latestCarts)
            ->whereHas('user', function ($query) {
                $query->whereNotNull('fcm_token');
            })
            ->get();

        foreach ($carts as $cart) {
            $user = $cart->user;

            // Skip if cart is less than 1 hour old
            if ($cart->created_at->gt($now->subHour())) {
                continue;
            }

            // Case: cart_notif_date is null
            if (!$user->cart_notif_date) {
                $notifTime = $cart->created_at->addHours(4);
                $template = NotificationTemplate::where('id', 6)->first();

                if ($notifTime->lte($now)) {
                    // Send notification
                    Notification::create([
                        'title' => $template->title,
                        'description' => $template->description,
                        'date' => now()->addHours(3),
                        'sent' => 0,
                        'type' => 'order_notif',
                        'type_receiver' => 'specific',
                        'users' => json_encode([$user->id]),
                    ]);

                    $user->update(['cart_notif_date' => $now]);
                }
            }
            // Case: cart_notif_date exists
            else {
                $lastNotifDate = Carbon::parse($user->cart_notif_date);

                // Ensure it's been more than 72 hours since last notification
                if ($lastNotifDate->lt($now->subHours(72)) && $cart->created_at->gt($lastNotifDate)) {
                    // Send notification
                    Notification::create([
                        'title' => 'هل نسيت سلتك؟',
                        'description' => 'منتجاتك ما زالت في السلة، أتمم طلبك الآن!',
                        'date' => now(),
                        'sent' => 0,
                        'type' => 'ads_notif',
                        'type_receiver' => 'specific',
                        'users' => json_encode([$user->id]),
                    ]);

                    $user->update(['cart_notif_date' => $now]);
                }
            }
        }
    }

public function notifyTomorrowBirthdays()
{
    try {
        // Get tomorrow's date in Saudi Arabia timezone
        $tomorrow = Carbon::now('Asia/Riyadh')->addDay();
        
        // Get users whose birthday is tomorrow (matching month and day)
        $users = User::whereNotNull('fcm_token')
            ->whereNotNull('birth_date')
            ->whereRaw('MONTH(birth_date) = ? AND DAY(birth_date) = ?', [
                $tomorrow->month,
                $tomorrow->day
            ])
            ->get();
        
        if ($users->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No users have birthdays tomorrow (' . $tomorrow->format('Y-m-d') . ')',
                'data' => [
                    'tomorrow_date' => $tomorrow->format('Y-m-d'),
                    'users_count' => 0,
                    'notifications_sent' => 0
                ]
            ]);
        }
        
        // Get notification template
        $template = NotificationTemplate::where('id', 7)->first();
        
        if (!$template) {
            return response()->json([
                'success' => false,
                'message' => 'Birthday notification template (ID: 7) not found',
                'data' => [
                    'tomorrow_date' => $tomorrow->format('Y-m-d'),
                    'users_count' => $users->count(),
                    'notifications_sent' => 0
                ]
            ]);
        }
        
        $notificationsSent = 0;
        
        foreach ($users as $user) {
            // Create notification for each user
            Notification::create([
                'title' => $template->title,
                'description' => $template->description,
                'date' => Carbon::now('Asia/Riyadh'), // Saudi Arabia time
                'sent' => 0,
                'type' => 'birthday_notif',
                'type_receiver' => 'specific',
                'users' => json_encode([$user->id]),
            ]);
            
            $notificationsSent++;
        }
        
        return response()->json([
            'success' => true,
            'message' => "Successfully sent birthday notifications to {$notificationsSent} users for tomorrow ({$tomorrow->format('Y-m-d')})",
            'data' => [
                'tomorrow_date' => $tomorrow->format('Y-m-d'),
                'users_count' => $users->count(),
                'notifications_sent' => $notificationsSent,
                'template_used' => [
                    'id' => $template->id,
                    'title' => $template->title
                ]
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error occurred while processing birthday notifications: ' . $e->getMessage(),
            'data' => [
                'tomorrow_date' => isset($tomorrow) ? $tomorrow->format('Y-m-d') : null,
                'users_count' => 0,
                'notifications_sent' => 0
            ]
        ]);
    }
}    
    public function addToCart(Request $request)
    {
        //Log::info('This is a simple log message from the controller.');

        $validated = $request->validate([
            'product_id' => 'required|exists:product,id',
            'user_id' => 'required|exists:users,id',
            'qte' => 'required|integer|min:1',
            'card_description' => 'nullable',
        ]);

        $validated['user_id'] = auth()->id() ?? $validated['user_id'] ;
        // Check if the item already exists in the cart for this user
        $cartItem = Carts::where('user_id', $validated['user_id'])
            ->where('product_id', $validated['product_id'])
            ->first();
            
        $validated['card_description'] =json_encode($validated['card_description']);

        if ($cartItem) {
        //   if ($cartItem->qte > 4) {
        //     return response()->json([
        //         'error' => "لا توجد كمية كافية من المنتج",
        //     ], 200);
        // }

            // If item exists, update the quantity
            $cartItem->qte += $validated['qte'];
            $cartItem->card_description = json_encode($validated['card_description']);
            $cartItem->save();
        } else {
            // Create new cart item
            $cartItem = Carts::create($validated);
        }
        return response()->json($cartItem, 201);
    }

    // Update cart item
    public function updateCartItem(Request $request, $id)
    {
        $validated = $request->validate([
            'qte' => 'integer|min:1',
            'preferred_delivery_start' => 'nullable|date|after:today',
            'preferred_delivery_end' => 'nullable|date|after:preferred_delivery_start',
        ]);

        $cartItem = Carts::findOrFail($id);
        $cartItem->update($validated);

        return response()->json($cartItem, 200);
    }

    // Remove item from cart
    public function removeFromCart($id)
    {
        $cartItem = Carts::findOrFail($id);
        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart'], 200);
    }

    // Get all items in the cart for a specific user
    public function getUserCart($userId)
    {
    // Retrieve cart items with their related product
    $cartItems = Carts::where('user_id', $userId)
        ->with('product')
        ->get();
    
    // Calculate total price of all items (price × quantity)
    $totalPrice = $cartItems->sum(function ($item) {
        return ( $item->product->discount_price ?? $item->product->price ) * $item->qte;
    });
    $qty = $cartItems->sum('qte');
    
    // Static values (set these as needed)
    $deliveryPrice     = config('company.delivery_cost'); // flat delivery fee
    $restForDiscount   = 0;  // remaining amount eligible for discount
    $restPercent       = 75;  // remaining discount percentage
    // $qty       = 75;  // remaining discount percentage
    $total = number_format($totalPrice + $deliveryPrice, 2, '.', '');
    $totalPrice = number_format($totalPrice, 2, '.', '');
    return response()->json([
        'items'               => $cartItems,
        'total_price'         => $totalPrice,
        'total'               => $totalPrice,
        'delivery_price'      => $deliveryPrice,
        'rest_for_discount'   => $restForDiscount,
        'qty'   => $qty,
        'rest_percent'        => $restPercent,
    ], 200);
    }
    
    public function getUserCartGroupedByMerchant($userId)
    {
        // Retrieve cart items with related product and merchant
        $userId = auth()->id() ?? $userId;
        // Log::info('User Merchant Cart logged in '.$userId);

        $cartItems = Carts::where('user_id', $userId)
            ->with(['product.merchant'])
            ->get();
            

        // Group cart items by merchant
        $grouped = $cartItems->groupBy(function ($item) {
            return $item->product->merchant->id ?? 0;
        });
    
        $result = [];
    
    foreach ($grouped as $merchantId => $items) {
        $merchant = $items->first()->product->merchant;
    
        // Adjust price based on discount
        $items = $items->map(function ($item) {
            if ($item->product && $item->product->discount_price !== null) {
                $item->product->price = $item->product->discount_price;
            }
            return $item;
        });
    
        $totalPrice = $items->sum(function ($item) {
            return $item->product->price * $item->qte;
        });
        $totalPrice = number_format($totalPrice, 2, '.', '');
        $deliveryPrice     = config('company.delivery_cost'); // flat delivery fee
    
        $is_free_delivery = false;
        $rest_for_discount = 0;
        $merchantFreeDelivery = $merchant->price_free_delivery;

        if($merchantFreeDelivery > 0 ){
            $is_free_delivery = true;
            $rest_for_discount = $merchantFreeDelivery - $totalPrice;
        }
        if($totalPrice > $merchantFreeDelivery && $is_free_delivery){
            $delivery_cost = 0 ;
        }


        $result[] = [
            'merchant' => $merchant,
            'items' => $items,
            'total_price' => $totalPrice,
            'total' => $totalPrice , // flat delivery fee
            'delivery_price' => 0,
            'rest_for_discount' => $rest_for_discount,
            'qty' => $items->sum('qte'),
            'rest_percent' => 75,
        ];
    }
        
            return response()->json($result, 200);
        }
    
    }
