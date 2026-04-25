<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\User\UnifiedAddressController;

use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\UserTrackingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth0Controller;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GoogleController;
use Laravel\Fortify\Features;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\OrderPrintController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\Admin\TopBannerController;
use App\Http\Controllers\Admin\ThemeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Keep your existing auth routes
Auth::routes();

// Fortify routes for two-factor authentication (if enabled)
if (Features::enabled(Features::twoFactorAuthentication())) {
    Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])
        ->middleware(['guest:'.config('fortify.guard')])
        ->name('two-factor.login');

    Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
        ->middleware(['guest:'.config('fortify.guard')])
        ->name('two-factor.verify');
}

// Two-Factor Authentication Routes
Route::middleware(['auth'])->group(function () {
    // 2FA Setup and Management
    Route::get('/two-factor-authentication', [TwoFactorController::class, 'index'])
        ->name('two-factor.index');

    Route::post('/two-factor-authentication/enable', [TwoFactorController::class, 'enable'])
        ->name('two-factor.enable');

    Route::delete('/two-factor-authentication/disable', [TwoFactorController::class, 'disable'])
        ->name('two-factor.disable');

    Route::post('/two-factor-authentication/recovery-codes', [TwoFactorController::class, 'generateNewRecoveryCodes'])
        ->name('two-factor.recovery.generate');
});

// 2FA Verification (for login)
Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/two-factor-challenge', [TwoFactorController::class, 'showVerificationForm'])
        ->name('two-factor.challenge');

    Route::post('/two-factor-challenge', [TwoFactorController::class, 'verify'])
        ->name('two-factor.verify');
});

// 2FA Recovery
Route::middleware(['auth'])->get('/two-factor-recovery', function () {
    return view('auth.two-factor-recovery');
})->name('two-factor.recovery');

// Stripe Webhook (should be outside CSRF protection)
Route::post('/stripe/webhook', [StripeController::class, 'webhook'])
    ->name('stripe.webhook');

// Routes for the homepage
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/load-more-products', [HomeController::class, 'loadMoreProducts'])->name('load.more.products');
Route::get('/shop', [ShopController::class,'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class,'product_details'])->name('shop.product.details');

// Cart routes
Route::get('/cart', [CartController::class,'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class,'add_to_cart'])->name('cart.add');
Route::put('cart/increase-quantity/{rowId}', [CartController::class,'increase_cart_quantity'])->name('cart.qty.increase');
Route::put('cart/decrease-quantity/{rowId}', [CartController::class,'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::delete('/cart/remove/{rowId}', [CartController::class,'remove_item'])->name('cart.item.remove');
Route::delete('/cart/clear', [CartController::class,'empty_cart'])->name('cart.empty');

// Coupon routes
Route::post('/cart/apply-coupon', [CartController::class,'apply_coupon_code'])->name('cart.coupon.apply');
Route::delete('/cart/remove-coupon', [CartController::class,'remove_coupon_code'])->name('cart.coupon.remove');

// Wishlist routes
Route::post('/wishlist/add', [WishlistController::class,'add_to_wishlist'])->name('wishlist.add');
Route::get('/wishlist', [WishlistController::class,'index'])->name('wishlist.index');
Route::delete('/wishlist/item/remove/{rowId}', [WishlistController::class,'remove_item'])->name('wishlist.item.remove');
Route::delete('/wishlist/clear', [WishlistController::class,'empty_wishlist'])->name('wishlist.item.clear');
Route::post('wishlist/move-to-cart/{rowId}', [WishlistController::class,'move_to_cart'])->name('wishlist.move.to.cart');

// Checkout routes
Route::get('/checkout', [CartController::class,'checkout'])->name('cart.checkout');
Route::post('/place-an-order', [CartController::class,'place_an_order'])->name('cart.place.an.order');
Route::get('/order-confirmation', [CartController::class,'order_confirmation'])->name('cart.order.confirmation');
Route::get('/contact-us', [HomeController::class,'contact'])->name('home.contact');
Route::post('/contact/store', [HomeController::class,'contact_store'])->name('home.contact.store');

// Stripe Payment Routes
Route::get('/stripe/payment/{order_id}', [StripeController::class, 'paymentPage'])
    ->name('stripe.payment')
    ->middleware('auth');

Route::post('/stripe/create-checkout', [StripeController::class, 'createCheckout'])
    ->name('stripe.create.checkout')
    ->middleware('auth');

Route::get('/stripe/success', [StripeController::class, 'success'])
    ->name('stripe.success')
    ->middleware('auth');

Route::get('/search', [HomeController::class,'search'])->name('home.search');

// Review routes
Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');
Route::middleware(['auth'])->group(function () {
    Route::post('/review/approve/{id}', [ReviewController::class, 'approve'])
        ->name('review.approve');
    Route::delete('/review/delete/{id}', [ReviewController::class, 'destroy'])
        ->name('review.destroy');
});

// User middleware routes
Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/account-orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/account-order/{order_id}/details', [UserController::class, 'order_details'])->name('user.order.details');
    Route::put('/account-order/cancel-order', [UserController::class, 'order_cancel'])->name('user.order.cancel');

    // User dashboard
    Route::get('/account-details', [UserController::class, 'account_details'])->name('user.account.details');
    Route::put('/account-details/update', [UserController::class, 'account_details_update'])->name('user.account.details.update');
    Route::get('/addresses', [UserController::class, 'addresses'])->name('user.addresses');
    Route::post('/addresses/store', [UserController::class, 'address_store'])->name('user.addresses.store');
    Route::put('/addresses/{id}/update', [UserController::class, 'address_update'])->name('user.addresses.update');
    Route::delete('/addresses/{id}/delete', [UserController::class, 'address_delete'])->name('user.addresses.delete');
 // Messages
    Route::get('/messages', [UserController::class, 'messages'])->name('user.messages');
    Route::get('/message/{id}', [UserController::class, 'messageDetails'])->name('user.message.details');


    //refunds
    Route::post('/refund/request', [RefundController::class, 'requestRefund'])
        ->name('refund.request');
    // User refund details route
Route::get('/user/refund/{id}', [RefundController::class, 'viewRefundDetails'])->name('user.refund.details');

    Route::get('/account/refunds', [RefundController::class, 'getUserRefunds'])
        ->name('user.refunds');

    Route::get('/user/reviews', [UserController::class, 'reviews'])->name('user.reviews')->middleware('auth');
    Route::delete('/user/reviews/{id}', [UserController::class, 'deleteReview'])->name('user.review.delete');

    // Security settings
    Route::get('/account/security', [UserController::class, 'security'])->name('user.security');
    Route::post('/account/sessions/{id}/logout', [UserController::class, 'logoutSession'])->name('user.session.logout');
    Route::post('/account/sessions/logout-all', [UserController::class, 'logoutAllSessions'])->name('user.session.logout.all');

    // Tracking
    Route::get('/tracking', [UserTrackingController::class, 'index'])->name('tracking.index');
    Route::get('/tracking/{order_id}', [UserTrackingController::class, 'show'])->name('tracking.show');
    Route::get('/tracking/{order_id}/print', [UserTrackingController::class, 'print'])->name('tracking.print');
    Route::get('/order/{order_id}/tracking', [UserOrderController::class, 'trackOrder'])
        ->name('user.order.tracking');
    Route::get('/order/{order_id}/tracking/print', [UserOrderController::class, 'printTracking'])
        ->name('user.order.tracking.print');
    Route::get('/order/{order_id}/invoice', [UserOrderController::class, 'printInvoice'])
        ->name('user.order.invoice');

    // Cart persistence
    Route::post('/cart/save', [CartController::class, 'saveCart'])->name('cart.save');
    Route::post('/cart/load', [CartController::class, 'loadCart'])->name('cart.load');
    Route::get('/user/address/{id}', [AddressController::class, 'getAddress'])->name('user.addresses.get');
// Unified Address Routes
Route::get('/addresses', [App\Http\Controllers\User\UnifiedAddressController::class, 'index'])->name('user.addresses.index');
Route::post('/addresses', [App\Http\Controllers\User\UnifiedAddressController::class, 'store'])->name('user.addresses.store');
Route::put('/addresses/{address}', [App\Http\Controllers\User\UnifiedAddressController::class, 'update'])->name('user.addresses.update');
Route::delete('/addresses/{address}', [App\Http\Controllers\User\UnifiedAddressController::class, 'destroy'])->name('user.addresses.destroy');
Route::post('/addresses/{address}/set-default', [App\Http\Controllers\User\UnifiedAddressController::class, 'setDefault'])->name('user.addresses.set-default');
    // Email verification
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    // Variant check
    Route::post('/product/{id}/variant-check', [ShopController::class, 'checkVariant'])->name('product.variant.check');
});

// Refund routes
Route::get('/refund/request/{order_id}', [RefundController::class, 'showRefundForm'])->name('user.refund.request.form');
Route::post('/refund/request', [RefundController::class, 'requestRefund'])->name('refund.request.submit');
Route::get('/refunds', [RefundController::class, 'getUserRefunds'])->name('user.refunds');
Route::get('/refund/{refund_id}', [RefundController::class, 'viewRefundDetails'])->name('user.refund.details');
Route::delete('/refund/{refund_id}/cancel', [RefundController::class, 'cancelRefund'])->name('user.refund.cancel');
Route::get('/refund/{refund_id}/status', [RefundController::class, 'getRefundStatus'])->name('user.refund.status');

// ADMIN ROUTES
Route::middleware(['auth', AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // Brands
    Route::get('/admin/brands', [AdminController::class,'brands'])->name('admin.brands');
    Route::get('/admin/brand/add',[AdminController::class,'add_brand'])->name('admin.brand.add');
    Route::post('/admin/brand/store', [AdminController::class,'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}', [AdminController::class,'brand_edit'])->name('admin.brand.edit');
    Route::put('/admin/brand/update', [AdminController::class,'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/brand/{id}/delete', [AdminController::class,'brand_delete'])->name('admin.brand.delete');

    // Categories
    Route::get('/admin/categories', [AdminController::class,'categories'])->name('admin.categories');
    Route::get('/admin/category/add', [AdminController::class,'category_add'])->name('admin.category.add');
    Route::post('/admin/category/store', [AdminController::class,'category_store'])->name('admin.category.store');
    Route::get('/admin/category/{id}/edit', [AdminController::class,'category_edit'])->name('admin.category.edit');
    Route::put('/admin/category/update', [AdminController::class,'category_update'])->name('admin.category.update');
    Route::delete('/admin/category/{id}/delete', [AdminController::class,'category_delete'])->name('admin.category.delete');

    // Products
    Route::get('/admin/products', [AdminController::class,'products'])->name('admin.products');
    Route::get('/admin/product/add', [AdminController::class,'product_add'])->name('admin.product.add');
    Route::post('/admin/product/store', [AdminController::class,'product_store'])->name('admin.product.store');
    Route::get('/admin/product/{id}/edit', [AdminController::class,'product_edit'])->name('admin.product.edit');
    Route::put('/admin/product/update', [AdminController::class,'product_update'])->name('admin.product.update');
    Route::delete('/admin/product/{id}/delete', [AdminController::class,'product_delete'])->name('admin.product.delete');

    // Coupons
    Route::get('/admin/coupons', [AdminController::class,'coupons'])->name('admin.coupons');
    Route::get('/admin/coupon/add',[AdminController::class,'coupon_add'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store', [AdminController::class,'coupon_store'])->name('admin.coupon.store');
    Route::get('/admin/coupon/{id}/edit', [AdminController::class,'coupon_edit'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update', [AdminController::class,'coupon_update'])->name('admin.coupon.update');
    Route::delete('/admin/coupon/{id}/delete', [AdminController::class,'coupon_delete'])->name('admin.coupon.delete');

    // Orders
    Route::get('/admin/orders', [AdminController::class,'orders'])->name('admin.orders');
    Route::get('/admin/orders/{order_id}/details', [AdminController::class,'order_details'])->name('admin.order.details');
    Route::put('/admin/order/update-status', [AdminController::class,'update_order_status'])->name('admin.order.status.update');

    // Slides Management
    Route::get('/admin/slides/manage', [AdminController::class, 'slides_manage'])->name('admin.slides.manage');
    Route::post('/admin/slides/toggle-status', [AdminController::class, 'slides_toggle_status'])->name('admin.slides.toggle-status');
    Route::post('/admin/slides/quick-delete', [AdminController::class, 'slides_quick_delete'])->name('admin.slides.quick-delete');

    // Slides
    Route::get('/admin/slides', [AdminController::class,'slides'])->name('admin.slides');
    Route::get('/admin/slide/add', [AdminController::class,'slide_add'])->name('admin.slide.add');
    Route::post('/admin/slide/store', [AdminController::class,'slide_store'])->name('admin.slide.store');
    Route::get('/admin/slide/{id}/edit', [AdminController::class,'slide_edit'])->name('admin.slide.edit');
    Route::put('/admin/slide/update', [AdminController::class,'slide_update'])->name('admin.slide.update');
    Route::delete('/admin/slide/{id}/delete', [AdminController::class,'slide_delete'])->name('admin.slide.delete');
   // Color Palettes Management
    Route::get('/color-palettes', [AdminController::class, 'color_palettes'])->name('admin.color-palettes');
    Route::get('/color-palettes/add', [AdminController::class, 'color_palette_add'])->name('admin.color-palette.add');
    Route::post('/color-palettes/store', [AdminController::class, 'color_palette_store'])->name('admin.color-palette.store');
   Route::get('/color-palettes/{id}/edit', [AdminController::class, 'color_palette_edit'])
    ->name('admin.color-palette.edit');    Route::put('/color-palettes/update', [AdminController::class, 'color_palette_update'])->name('admin.color-palette.update');
    Route::patch('/color-palettes/{id}/activate', [AdminController::class, 'color_palette_activate'])->name('admin.color-palette.activate');
    Route::delete('/color-palettes/{id}', [AdminController::class, 'color_palette_delete'])->name('admin.color-palette.delete');
    // Email Management Dashboard
    Route::get('/admin/emails', [EmailController::class, 'index'])->name('admin.emails.index');
    Route::get('/admin/emails/compose', [EmailController::class, 'compose'])->name('admin.emails.compose');
    Route::post('/admin/emails/send', [EmailController::class, 'send'])->name('admin.emails.send');
    Route::post('/admin/emails/bulk', [EmailController::class, 'bulkSend'])->name('admin.emails.bulk');
    Route::post('/admin/emails/test', [EmailController::class, 'testEmail'])->name('admin.emails.test');

    // Email Templates
    Route::get('/admin/emails/templates', [EmailController::class, 'templates'])->name('admin.emails.templates');
    Route::get('/admin/emails/templates/preview/{template}', [EmailController::class, 'previewTemplate'])->name('admin.emails.templates.preview');

    // Email Statistics (JSON)
    Route::get('/admin/emails/statistics/json', [EmailController::class, 'getEmailStatistics'])->name('admin.emails.statistics.json');

    // AJAX routes for users email interface
    Route::get('/admin/emails/get-users', [EmailController::class, 'getUsers'])->name('admin.emails.get-users');
    Route::post('/admin/emails/send-user', [EmailController::class, 'sendUserEmail'])->name('admin.emails.send-user');
    Route::post('/admin/emails/send-welcome', [EmailController::class, 'sendWelcomeEmail'])->name('admin.emails.send-welcome');
    Route::post('/admin/emails/send-bulk', [EmailController::class, 'sendBulk'])->name('admin.emails.send-bulk');

    // Order Email Actions
    Route::post('/admin/order/{order_id}/email/confirmation', [EmailController::class, 'sendOrderConfirmationEmail'])
        ->name('admin.order.send.confirmation.email');

    Route::post('/admin/order/{order_id}/email/invoice', [EmailController::class, 'sendOrderInvoiceEmail'])
        ->name('admin.order.send.invoice.email');

    Route::post('/admin/order/{order_id}/email/summary', [EmailController::class, 'sendOrderSummaryEmail'])
        ->name('admin.order.send.summary.email');

    Route::post('/admin/order/{order_id}/email/status', [EmailController::class, 'sendOrderStatusEmail'])
        ->name('admin.order.send.status.email');

    Route::post('/admin/order/{order_id}/email/quick', [EmailController::class, 'quickEmailAction'])
        ->name('admin.order.quick.email');

    // Users
    Route::put('/user/update', [AdminController::class, 'user_update'])->name('admin.user.update-route');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/user/view/{id}', [AdminController::class, 'user_view'])->name('admin.user.view');
    Route::delete('/user/delete/{id}', [AdminController::class, 'user_delete'])->name('admin.user.delete');
    Route::put('/user/update-role/{id}', [AdminController::class, 'user_update_role'])->name('admin.user.update.role');
    Route::get('/user/edit/{id}', [AdminController::class, 'user_edit'])->name('admin.user-edit');

    // Contact
    Route::get('/admin/contact', [AdminController::class,'contacts'])->name('admin.contacts');
    Route::delete('/admin/contact/{id}/delete', [AdminController::class,'contact_delete'])->name('admin.contact.delete');
    Route::get('/contact/{id}', [AdminController::class, 'contact_details'])->name('admin.contact.details');
    Route::get('/contact-mark-read/{id}', [AdminController::class, 'contact_mark_read'])->name('contact.mark-read');
    Route::post('/contact-reply/{id}', [AdminController::class, 'contact_reply'])->name('admin.contact.reply');
    Route::post('/search/contacts', [AdminController::class, 'search_contacts'])->name('search.contacts');
    Route::get('/admin/contact/mark-read/{id}', [AdminController::class, 'contact_mark_read'])
        ->name('admin.contact.mark-read');

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::put('/settings/update', [AdminController::class, 'settings_update'])->name('admin.settings.update');
    Route::post('/settings/change-password', [AdminController::class, 'change_password'])->name('admin.settings.change.password');

    // Notifications
    Route::get('/notifications/load', [NotificationController::class, 'loadNotifications'])
         ->name('admin.notifications.load');
    Route::post('/notification/mark-read', [NotificationController::class, 'markAsRead'])
         ->name('admin.notification.mark-read');
    Route::get('/notifications/counts', [AdminController::class, 'getNotificationCounts'])
         ->name('notifications.counts');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
         ->name('admin.notification.mark-all-read');
    Route::get('/notifications', [NotificationController::class, 'index'])
         ->name('admin.notifications.all');
    Route::get('/admin/notifications/counts', [AdminController::class, 'getNotificationCounts'])
        ->name('admin.notifications.counts');
    Route::post('/admin/notifications/counts', [AdminController::class, 'getNotificationCounts'])
        ->name('admin.notifications.counts.post');

    // Dashboard stats
    Route::get('/admin/dashboard/stats', [AdminController::class, 'getDashboardStats']);
    Route::get('/admin/dashboard/chart-data', [AdminController::class, 'getChartData']);

    // Search
    Route::get('/rechercher', [AdminController::class,'rechercher'])->name('admin.rechercher');

    // Blog
    Route::get('/blogs', [AdminController::class, 'blogs'])->name('admin.blogs');
    Route::get('/blog/add', [AdminController::class, 'blog_add'])->name('admin.blog.add');
    Route::post('/blog/store', [AdminController::class, 'blog_store'])->name('admin.blog.store');
    Route::get('/blog/edit/{id}', [AdminController::class, 'blog_edit'])->name('admin.blog.edit');
    Route::post('/blog/update', [AdminController::class, 'blog_update'])->name('admin.blog.update');
    Route::delete('/blog/delete/{id}', [AdminController::class, 'blog_delete'])->name('admin.blog.delete');
    Route::get('/blog/toggle-featured/{id}', [AdminController::class, 'blog_toggle_featured'])->name('admin.blog.toggle.featured');
    Route::get('/blog/toggle-published/{id}', [AdminController::class, 'blog_toggle_published'])->name('admin.blog.toggle.published');
    Route::post('/admin/blog/upload-image', [AdminController::class, 'blog_upload_image'])->name('admin.blog.upload.image');

    // Sizes and Colors
    Route::get('/sizes', [SizeController::class, 'index'])->name('admin.sizes');
    Route::get('/size/add', [SizeController::class, 'create'])->name('admin.size.add');
    Route::post('/size/store', [SizeController::class, 'store'])->name('admin.size.store');
    Route::get('/size/edit/{id}', [SizeController::class, 'edit'])->name('admin.size.edit');
    Route::put('/size/update', [SizeController::class, 'update'])->name('admin.size.update');
    Route::delete('/size/delete/{id}', [SizeController::class, 'destroy'])->name('admin.size.delete');

    Route::get('/colors', [ColorController::class, 'index'])->name('admin.colors');
    Route::get('/color/add', [ColorController::class, 'create'])->name('admin.color.add');
    Route::post('/color/store', [ColorController::class, 'store'])->name('admin.color.store');
    Route::get('/color/edit/{id}', [ColorController::class, 'edit'])->name('admin.color.edit');
    Route::put('/color/update', [ColorController::class, 'update'])->name('admin.color.update');
    Route::delete('/color/delete/{id}', [ColorController::class, 'destroy'])->name('admin.color.delete');

    // Refund System
    Route::get('/admin/refunds', [RefundController::class, 'getAdminRefunds'])->name('admin.refunds');
    Route::post('/admin/refund/process', [RefundController::class, 'processRefund'])->name('admin.refund.process');
    Route::post('/admin/refund/quick-action', [RefundController::class, 'quickAction'])->name('admin.refund.quick-action');
    Route::get('/admin/refund/{id}/details', [RefundController::class, 'getRefundDetails'])->name('admin.refund.details');
    Route::get('/admin/refund/export', [RefundController::class, 'exportRefunds'])->name('admin.refund.export');
    Route::put('/admin/refund/{id}/update', [RefundController::class, 'updateRefund'])->name('admin.refund.update');
    Route::get('/admin/refund/stats', [RefundController::class, 'getRefundStats'])->name('admin.refund.stats');
    Route::delete('/admin/refund/{id}/delete', [RefundController::class, 'deleteRefund'])->name('admin.refund.delete');
    Route::get('/admin/refund/{id}/print', [RefundController::class, 'print'])->name('admin.refund.print');
// In routes/web.php or routes/admin.php
Route::post('/admin/refund/send-email', [RefundController::class, 'sendEmail'])->name('admin.refund.send-email');
Route::get('/admin/refund/{id}/email-history', [RefundController::class, 'emailHistory'])->name('admin.refund.email-history');
    // Category Banners
    Route::get('/category-banners', [AdminController::class, 'category_banners'])->name('admin.category-banners');
    Route::get('/category-banner/add', [AdminController::class, 'category_banner_add'])->name('admin.category-banner.add');
    Route::post('/category-banner/store', [AdminController::class, 'category_banner_store'])->name('admin.category-banner.store');
    Route::get('/category-banner/edit/{id}', [AdminController::class, 'category_banner_edit'])->name('admin.category-banner.edit');
    Route::put('/category-banner/update', [AdminController::class, 'category_banner_update'])->name('admin.category-banner.update');
    Route::delete('/category-banner/delete/{id}', [AdminController::class, 'category_banner_delete'])->name('admin.category-banner.delete');

    // Reviews
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin.reviews');
    Route::post('/review/{id}/approve', [AdminController::class, 'approveReview'])->name('admin.review.approve');
    Route::patch('/review/{id}/decline', [AdminController::class, 'declineReview'])->name('admin.review.decline');
    Route::patch('/review/{id}/unapprove', [AdminController::class, 'unapproveReview'])->name('admin.review.unapprove');
    Route::post('/review/{id}/restore', [AdminController::class, 'restoreReview'])->name('admin.review.restore');
    Route::delete('/review/{id}/delete', [AdminController::class, 'deleteReview'])->name('admin.review.delete');

    // Shop Banners
    Route::get('/admin/shop-banners', [AdminController::class, 'shop_banners'])->name('admin.shop-banners');
    Route::get('/admin/shop-banner/add', [AdminController::class, 'shop_banner_add'])->name('admin.shop-banner.add');
    Route::post('/admin/shop-banner/store', [AdminController::class, 'shop_banner_store'])->name('admin.shop-banner.store');
    Route::get('/admin/shop-banner/edit/{id}', [AdminController::class, 'shop_banner_edit'])->name('admin.shop-banner.edit');
    Route::put('/admin/shop-banner/update', [AdminController::class, 'shop_banner_update'])->name('admin.shop-banner.update');
    Route::delete('/admin/shop-banner/delete/{id}', [AdminController::class, 'shop_banner_delete'])->name('admin.shop-banner.delete');

    // PRINT ROUTES
    // Single order print routes
    Route::get('/order/{order_id}/print/invoice', [OrderPrintController::class, 'printInvoice'])
        ->name('admin.order.print.invoice');
    Route::get('/order/{order_id}/print/shipping-label', [OrderPrintController::class, 'printShippingLabel'])
        ->name('admin.order.print.shipping-label');
    Route::get('/order/{order_id}/print/packing-slip', [OrderPrintController::class, 'printPackingSlip'])
        ->name('admin.order.print.packing-slip');
    Route::get('/order/{order_id}/print/order-summary', [OrderPrintController::class, 'printOrderSummary'])
        ->name('admin.order.print.order-summary');
    Route::get('/order/{order_id}/print/delivery-note', [OrderPrintController::class, 'printDeliveryNote'])
        ->name('admin.order.print.delivery-note');

    // Batch print routes
    Route::post('/batch/print/invoices', [OrderPrintController::class, 'batchPrintInvoices'])
        ->name('admin.order.batch.print.invoices');
    Route::post('/batch/print/shipping-labels', [OrderPrintController::class, 'batchPrintShippingLabels'])
        ->name('admin.order.batch.print.shipping-labels');
    Route::post('/batch/print/summaries', [OrderPrintController::class, 'batchPrintSummaries'])
        ->name('admin.order.batch.print.summaries');

    // Export routes
    Route::get('/order/{order_id}/export/csv', [OrderPrintController::class, 'exportOrderCSV'])
        ->name('admin.order.export.csv');
    Route::get('/order/{order_id}/export/json', [OrderPrintController::class, 'exportOrderJSON'])
        ->name('admin.order.export.json');

    // Archive route
    Route::post('/order/{order_id}/archive', [OrderPrintController::class, 'archiveOrder'])
        ->name('admin.order.archive');

    // Top Banner
    Route::get('top-banners', [TopBannerController::class, 'index'])->name('admin.top-banners.index');
    Route::get('top-banners/create', [TopBannerController::class, 'create'])->name('admin.top-banners.create');
    Route::post('top-banners', [TopBannerController::class, 'store'])->name('admin.top-banners.store');
    Route::get('top-banners/{id}/edit', [TopBannerController::class, 'edit'])->name('admin.top-banners.edit');
    Route::put('top-banners/{id}', [TopBannerController::class, 'update'])->name('admin.top-banners.update');
    Route::delete('top-banners/{id}', [TopBannerController::class, 'destroy'])->name('admin.top-banners.destroy');
    Route::patch('top-banners/{id}/toggle-active', [TopBannerController::class, 'toggleActive'])->name('admin.top-banners.toggle-active');
});

// Search Routes (POST)
Route::post('/admin/search-brands', [AdminController::class, 'search_brands'])->name('admin.search.brands');
Route::post('/admin/search-categories', [AdminController::class, 'search_categories'])->name('admin.search.categories');
Route::post('/admin/search-products', [AdminController::class, 'search_products'])->name('admin.search.products');
Route::post('/admin/search-coupons', [AdminController::class, 'search_coupons'])->name('admin.search.coupons');
Route::post('/admin/search-orders', [AdminController::class, 'search_orders'])->name('admin.search.orders');
Route::post('/admin/search-contacts', [AdminController::class, 'search_contacts'])->name('admin.search.contacts');
Route::post('/admin/search-users', [AdminController::class, 'search_users'])->name('admin.search.users');
Route::post('/admin/search-colors', [AdminController::class, 'search_colors'])->name('admin.search.colors');
Route::post('/admin/search-sizes', [AdminController::class, 'search_sizes'])->name('admin.search.sizes');

// Public tracking (no auth required, uses token)
Route::get('/track-order/{order_id}/{token}', [UserOrderController::class, 'publicTrackOrder'])
    ->name('public.order.tracking');

// Quick tracking form
Route::post('/tracking/quick', [UserTrackingController::class, 'quickTrack'])->name('tracking.quick');

// Blog Routes (public)
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Variant check API
Route::post('/api/product/{product}/variant-check', [ShopController::class, 'checkVariant'])->name('product.variant.check');

// Email Verification Routes
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->middleware(['auth'])
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// Google Authentication
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Guest checkout routes
Route::post('/checkout/guest', [CartController::class, 'guestCheckout'])->name('cart.checkout.guest');
Route::post('/checkout/guest/complete', [CartController::class, 'completeGuestCheckout'])->name('cart.checkout.guest.complete');

// Test barcode generation
Route::get('/test-barcode/{id}', function($id) {
    $barcodeService = app(\App\Services\BarcodeService::class);
    $barcodes = $barcodeService->getAllBarcodes($id);
    return view('test-barcode', compact('barcodes', 'id'));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
