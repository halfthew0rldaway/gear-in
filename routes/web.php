<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'index'])->name('home');
Route::get('/catalog', [StorefrontController::class, 'catalog'])->name('catalog');
Route::get('/catalog/search', [StorefrontController::class, 'searchSuggestions'])->name('catalog.search');
Route::get('/categories/{category:slug}', [StorefrontController::class, 'category'])->name('categories.show');
Route::get('/products/{product:slug}', [StorefrontController::class, 'show'])->name('products.show');

Route::get('/dashboard', function () {
    if (auth()->user()?->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'can:access-customer'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart/bulk-delete', [CartController::class, 'bulkDelete'])->name('cart.bulk-delete');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/payment/{order}', [\App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{order}/complete', [\App\Http\Controllers\PaymentController::class, 'complete'])->name('payment.complete');
    Route::get('/payment/{order}/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');

    Route::post('/products/{product}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [\App\Http\Controllers\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
    Route::post('/chat/{conversation}/message', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/send-ajax', [\App\Http\Controllers\ChatController::class, 'sendMessageAjax'])->name('chat.send-ajax');
    Route::get('/chat/{conversation}/messages', [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');

    Route::post('/voucher/validate', [\App\Http\Controllers\VoucherController::class, 'validate'])->name('voucher.validate');
    Route::post('/promo-widget/close', [\App\Http\Controllers\PromoWidgetController::class, 'close'])->name('promo-widget.close');
    Route::post('/promo-widget/minimize', [\App\Http\Controllers\PromoWidgetController::class, 'minimize'])->name('promo-widget.minimize');
    Route::post('/promo-widget/reset', [\App\Http\Controllers\PromoWidgetController::class, 'reset'])->name('promo-widget.reset');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'can:access-admin'])
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/printable', [AdminDashboardController::class, 'printable'])->name('dashboard.printable');
        Route::resource('categories', AdminCategoryController::class)->except(['show']);
        Route::post('products/{product}/restore', [AdminProductController::class, 'restore'])->name('products.restore');
        Route::delete('products/{product}/force-delete', [AdminProductController::class, 'forceDelete'])->name('products.force-delete');
        Route::resource('products', AdminProductController::class)->except(['show']);

        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::get('orders/{order}/receipt', [AdminOrderController::class, 'receipt'])->name('orders.receipt');
        Route::patch('orders/{order}/assign', [AdminOrderController::class, 'assign'])->name('orders.assign');
        Route::patch('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

        Route::get('reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::get('reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('reviews.show');
        Route::post('reviews/{review}/reply', [\App\Http\Controllers\Admin\ReviewController::class, 'reply'])->name('reviews.reply');

        Route::get('chat', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat.index');
        Route::get('chat/{conversation}', [\App\Http\Controllers\Admin\ChatController::class, 'show'])->name('chat.show');
        Route::post('chat/{conversation}/message', [\App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('chat.send');
        Route::get('chat/{conversation}/messages', [\App\Http\Controllers\Admin\ChatController::class, 'getMessages'])->name('chat.messages');
        Route::patch('chat/{conversation}/status', [\App\Http\Controllers\Admin\ChatController::class, 'updateStatus'])->name('chat.update-status');

        Route::resource('vouchers', \App\Http\Controllers\Admin\VoucherController::class);
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
