<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\QuickOrderController;

// Root → admin login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);

Route::view('/privacy-policy', 'privacy')->name('privacy');
Route::view('/account-deletion', 'account-deletion')->name('account-deletion');
Route::view('/support', 'support')->name('support');

// Public product page
Route::get('/product/{id}', [ProductPageController::class, 'show'])->name('product.show');
Route::post('/quick-order', [QuickOrderController::class, 'store'])->name('quick-order.store');

// Dashboard
Route::get('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard');

// Admin panel (requires admin guard)
Route::prefix('dashboard')->middleware('auth:admin')->group(function () {

    // Categories
    Route::get('/category', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'index'])->name('admin.category.index');
    Route::get('/category/add', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'showAdd'])->name('admin.category.add');
    Route::post('/category/store', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/category/{id}/edit', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'showEdit'])->name('admin.category.edit');
    Route::put('/category/{id}', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'update'])->name('admin.category.update');
    Route::get('/category/{id}/delete', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'destroy'])->name('admin.category.destroy');
    Route::get('/category/{id}/visiblity', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'visiblity'])->name('admin.category.visiblity');

    // Banners
    Route::get('/banner', [\App\Http\Controllers\Admin\AdminBannerController::class, 'index'])->name('admin.banner.index');
    Route::get('/banner/add', [\App\Http\Controllers\Admin\AdminBannerController::class, 'showAdd'])->name('admin.banner.add');
    Route::post('/banner/store', [\App\Http\Controllers\Admin\AdminBannerController::class, 'store'])->name('admin.banner.store');
    Route::get('/banner/{id}/edit', [\App\Http\Controllers\Admin\AdminBannerController::class, 'showEdit'])->name('admin.banner.edit');
    Route::put('/banner/{id}', [\App\Http\Controllers\Admin\AdminBannerController::class, 'update'])->name('admin.banner.update');
    Route::get('/banner/{id}/delete', [\App\Http\Controllers\Admin\AdminBannerController::class, 'destroy'])->name('admin.banner.destroy');
    Route::get('/banner/{id}/visibility', [\App\Http\Controllers\Admin\AdminBannerController::class, 'visibility'])->name('admin.banner.visibility');

    // Merchants
    Route::get('/merchants', [\App\Http\Controllers\Admin\AdminMerchantController::class, 'index'])->name('admin.merchant.index');
    Route::get('/merchants/add', [\App\Http\Controllers\Admin\AdminMerchantController::class, 'showAdd'])->name('admin.merchant.add');
    Route::post('/merchants/store', [\App\Http\Controllers\Admin\AdminMerchantController::class, 'store'])->name('admin.merchant.store');
    Route::get('/merchants/{id}/edit', [\App\Http\Controllers\Admin\AdminMerchantController::class, 'showEdit'])->name('admin.merchant.edit');
    Route::put('/merchants/{id}', [\App\Http\Controllers\Admin\AdminMerchantController::class, 'update'])->name('admin.merchant.update');
    Route::get('/merchants/{id}/delete', [\App\Http\Controllers\Admin\AdminMerchantController::class, 'destroy'])->name('admin.merchant.destroy');
    Route::get('/merchants/{id}/visibility', [\App\Http\Controllers\Admin\AdminMerchantController::class, 'visibility'])->name('admin.merchant.visibility');

    // Products
    Route::get('/products', [\App\Http\Controllers\Admin\AdminProductController::class, 'index'])->name('admin.product.index');
    Route::get('/products/add', [\App\Http\Controllers\Admin\AdminProductController::class, 'showAdd'])->name('admin.products.add');
    Route::post('/products/store', [\App\Http\Controllers\Admin\AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [\App\Http\Controllers\Admin\AdminProductController::class, 'showEdit'])->name('admin.products.edit');
    Route::put('/products/{id}', [\App\Http\Controllers\Admin\AdminProductController::class, 'update'])->name('admin.products.update');
    Route::get('/products/{id}/delete', [\App\Http\Controllers\Admin\AdminProductController::class, 'delete'])->name('admin.products.delete');
    Route::get('/products/{id}/visibility', [\App\Http\Controllers\Admin\AdminProductController::class, 'destroy'])->name('admin.products.visibility');

    // Variant management
    Route::get('/products/{id}/variants',  [\App\Http\Controllers\Admin\AdminProductController::class, 'getVariants'])->name('admin.products.variants.get');
    Route::post('/products/{id}/variants', [\App\Http\Controllers\Admin\AdminProductController::class, 'updateVariants'])->name('admin.products.variants.update');

    // Option value image upload (AJAX)
    Route::post('/products/upload-option-image', function (\Illuminate\Http\Request $request) {
        $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048']);
        $file = $request->file('image');
        $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/option_values'), $name);
        return response()->json(['path' => 'uploads/option_values/' . $name, 'url' => url('uploads/option_values/' . $name)]);
    })->name('admin.option.upload-image');

    // Option usage count (AJAX)
    Route::get('/products/option-usage', function (\Illuminate\Http\Request $request) {
        $name  = $request->input('name', '');
        $count = \App\Models\ProductOption::where('name', $name)->count();
        return response()->json(['count' => $count]);
    })->name('admin.option.usage');

    // Product approval
    Route::get('/product_approve', [\App\Http\Controllers\Admin\AdminProductController::class, 'indexApprove'])->name('admin.product.indexApprove');
    Route::get('/product_approve/{id}', [\App\Http\Controllers\Admin\AdminProductController::class, 'ApproveProduct'])->name('admin.product.approve');

    // Orders
    Route::get('/orders', [\App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{id}/status', [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::post('/orders/{id}/payment', [\App\Http\Controllers\Admin\AdminOrderController::class, 'updatePaymentStatus'])->name('admin.orders.updatePayment');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
