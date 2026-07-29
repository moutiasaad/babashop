<?php
use App\Http\Controllers\Api\AuthController; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Driver\AuthDriverController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MerchantController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AccountDeletionController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\DeliveryZoneController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/auth/create-guest', [AuthController::class, 'createGuestUser']);
Route::post('/auth/refresh-token', [AuthController::class, 'refreshToken']);
Route::post('/register-phone', [AuthController::class, 'registerPhone']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

// Email OTP — alternate channel to SMS
Route::post('/auth/email/request-otp', [AuthController::class, 'requestEmailOtp']);
Route::post('/auth/email/verify-otp', [AuthController::class, 'verifyEmailOtp']);
Route::prefix('drivers')->group(function () {
    Route::post('/register-phone', [AuthDriverController::class, 'registerPhone']);
    Route::post('/verify-otp', [AuthDriverController::class, 'verifyOtp']);
    Route::get('/status/{orderid}', [OrderDriverController::class, 'status']);
});

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/update-user-info-manual', [AuthController::class, 'updateUserInfomations']);
        Route::post('/destroy-account', [AuthController::class, 'destroyAccount']);
    });
// Banners — public read only (admin panel manages write)
Route::get('/banners', [BannerController::class, 'index']);

// Delivery zones — public read; app checkout uses this to render governorate picker + preview fees
Route::get('/delivery-zones', [DeliveryZoneController::class, 'index']);
Route::get('/delivery-zones/{deliveryZone}', [DeliveryZoneController::class, 'show']);

// Support & account deletion — public
Route::post('/support-request', [SupportController::class, 'store']);
Route::post('/account-deletion-request', [AccountDeletionController::class, 'store']);

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);  // List all categories (protected)
    Route::get('/{category}', [CategoryController::class, 'show']);  // Show a specific category by ID (protected)
    Route::get('/{category}/products', [CategoryController::class, 'getProductsByCategory']);  // Get all products in a category (protected)
});
Route::get('/rules', [MerchantController::class, 'getTermsAndConditionsHtml']);

Route::prefix('merchants')->group(function () {
    Route::get('/', [MerchantController::class, 'index']);
    Route::get('/categories', [MerchantController::class, 'indexMerchantCategories']);
    Route::get('/{merchant}', [MerchantController::class, 'show']);
    // Route::get('/type/{type}', [MerchantController::class, 'getByType']);
    Route::get('/search/{name}', [MerchantController::class, 'searchByName'])->name('merchants.search');
    Route::get('/{merchant}/products', [MerchantController::class, 'getProductByMerchant']);
    Route::get('/{merchant}/custom_products', [MerchantController::class, 'getProductCustomeByMerchant']);

});
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/search/{name}', [ProductController::class, 'searchByName'])->name('products.search');
    Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');

    // Reviews — public read, auth write/delete
    Route::get('/{product}/reviews', [ReviewController::class, 'index']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/{product}/reviews', [ReviewController::class, 'store']);
        Route::delete('/{product}/reviews', [ReviewController::class, 'destroy']);
    });

    // Product options — public read, auth write
    Route::get('/{product}/options', [ProductController::class, 'listOptions']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/{product}/options', [ProductController::class, 'storeOption']);
        Route::put('/{product}/options/{option}', [ProductController::class, 'updateOption']);
        Route::delete('/{product}/options/{option}', [ProductController::class, 'destroyOption']);
    });
});
Route::get('/notifyAbandonedCarts', [CartController::class, 'notifyAbandonedCarts']); // Add item to cart
    Route::get('/notifyTomorrowBirthdays', [CartController::class, 'notifyTomorrowBirthdays']);
    
Route::middleware('auth:sanctum')->group(function () {
    //
    Route::post('/update-user-info', [AuthController::class, 'updateUserInfo']);
    Route::post('/auth/get-user-info', [AuthController::class, 'getUserInfo']);
    Route::prefix('cart')->group(function () {
        Route::post('/', [CartController::class, 'addToCart']); // Add item to cart
        Route::put('/{id}', [CartController::class, 'updateCartItem']); // Update cart item
        Route::delete('/{id}', [CartController::class, 'removeFromCart']); // Remove item from cart
        Route::get('/user/{userId}', [CartController::class, 'getUserCart']); // Get all items in a user's cart
        // Route::get('/userMerchant/{userId}', [CartController::class, 'getUserCartGroupedByMerchant']); // Get all items in a user's cart
    }); // Add item to cart
    Route::prefix('orders')->group(function () {
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/statuses', [OrderController::class, 'statuses']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::post('/update', [OrderController::class, 'orderUpdate']);
        Route::post('/apply-coupon', [OrderController::class, 'validateCouponOnOrder']); // ← new
        Route::delete('/{order}', [OrderController::class, 'destroy']);
        Route::get('/status/{order}', [OrderController::class, 'getOrderStatus']);
        Route::post("/makePayment", [MyFatoorahController::class , 'makePayment'])->name('makePayment');
                Route::get('/getPriceByCoupon/{coupon}/{cart}', [OrderController::class, 'getPriceByCoupon']);



    });
    Route::prefix('wishlist')->group(function () {
        Route::post('/{product}', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
        Route::get('/', [WishlistController::class, 'indexByUser'])->name('wishlist.index');
        Route::delete('/{productId}', [WishlistController::class, 'removeFromWishlist']);
    });

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
