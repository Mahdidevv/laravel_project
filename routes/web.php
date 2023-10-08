<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Home\AddressController;
use App\Http\Controllers\Home\CartController;
use App\Http\Controllers\Home\CommentController as HomeCommentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Home\CategoryController as HomeCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Home\CompareController;
use App\Http\Controllers\Home\PaymentController;
use App\Http\Controllers\Home\ProductController as HomeProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\UserProfileController;
use App\Http\Controllers\Home\WishlistController;
use App\Models\Coupon;
use App\Models\User;
use App\Notifications\OTPSms;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/admin-panel/dashboard',[AdminController::class,'index'])->name('dashboard');
// Index
Route::get('/',[HomeController::class,'index'])->name('index');
//Category
Route::get('/categories/{category:slug}',[HomeCategoryController::class,'show'])->name('home.categories.show');
//Show Single Product
Route::get('/products/{product:slug}',[HomeProductController::class,'show'])->name('home.products.show');
//logout
Route::get('/test',function (){
    auth()->loginUsingId(5);
});
//Login Request & Response & Callback
Route::any('/login',[AuthController::class,'login'])->name('login')->middleware('guest');
//Check OTPCode
Route::any('/login/check-otp',[AuthController::class,'checkOtp'])->name('check-otp');
//Resend OTPCode
Route::post('/login/resend-otp',[AuthController::class,'resendOtp'])->name('resend-otp');
//store Comment
Route::post('/comment/{product}',[HomeCommentController::class,'store'])->name('home.comments.store');
//add to wishlist
Route::get('/add-to-wishlist/{product}',[WishlistController::class,'add'])->name('home.wishlist.add');
//delete from wishlist
Route::get('/remove-from-wishlist/{product}',[WishlistController::class,'remove'])->name('home.wishlist.remove');
//remove compare product
Route::get('/remove-from-compare/{product}',[CompareController::class,'remove'])->name('home.compare.remove');
//add to compare
Route::get('/add-to-compare/{product}',[CompareController::class,'add'])->name('home.compare.add');
//compare
Route::get('/compare',[CompareController::class,'index'])->name('home.compare.index');
//add to cart
Route::post('/add-to-cart/{product}',[CartController::class,'add'])->name('home.cart.add');
//cart
Route::get('/cart',[CartController::class,'index'])->name('home.cart.index');
//cart update
Route::put('/cart/update',[CartController::class,'update'])->name('home.cart.update');
//cart remove
Route::get('/cart/remove-from-cart/{rowId}',[CartController::class,'remove'])->name('home.cart.remove');
//cart clear
Route::get('/cart/clear',[CartController::class,'clear'])->name('home.cart.clear');
//check coupon
Route::post('/coupon/check-coupon',[CartController::class,'checkCoupon'])->name('home.coupon.check-coupon');
// add address
Route::post('/addresses/store',[AddressController::class,'store'])->name('home.addresses.store');
//get cities and provinces
Route::get('/get-province-cities-list',[AddressController::class,'getProvinceCitiesList'])->name('home.addresses.get-cities-provinces');
//update Address
Route::put('/addresses/{address}',[AddressController::class,'update'])->name('home.addresses.update');
//checkout
Route::get('/checkout',[CartController::class,'checkout'])->name('home.cart.checkout');
//payment
Route::post('/payment',[PaymentController::class,'payment'])->name('home.payment');



//Admin
Route::prefix('admin-panel/management')->name('admin.')->group(function (){
   Route::resource('brands',BrandController::class);
   Route::resource('attributes',AttributeController::class);
   Route::resource('categories',CategoryController::class);
   Route::resource('tags',TagController::class);
   Route::resource('products',ProductController::class);
   Route::resource('banners',BannerController::class);
   Route::resource('comments',CommentController::class);
   Route::resource('coupons',CouponController::class);
   Route::resource('orders',OrderController::class);
   Route::resource('users',UserController::class)->middleware('role:Admin');
   Route::resource('permissions',PermissionController::class);
   Route::resource('roles',RoleController::class);

   //Get Attributes For Create Product
    Route::get('/category-attributes/{categoryId}',[CategoryController::class,'getCategoryAttributes']);
    // Edit Product Images
    Route::get('products/{product}/edit-images',[ProductImageController::class,'edit'])->name('products.edit.images');
    //Delete Product Image
    Route::delete('products/{product}/destroy-images',[ProductImageController::class,'destroy'])->name('products.delete.image');
    //set Primary Image
    Route::put('products/{product}/set-primary-images',[ProductImageController::class,'setPrimary'])->name('products.set.primary.image');
    //add Product Image
    Route::put('products/{product}/add-images',[ProductImageController::class,'add'])->name('products.add.image');
    //Edit Category Product
    Route::get('products/{product}/edit-category',[ProductController::class,'editCategory'])->name('products.edit.category');
    //Update Category Product
    Route::put('products/{product}/update-category',[ProductController::class,'updateCategory'])->name('products.update.category');
    //update approved comment
    Route::get('comments/{comment}/update-approved',[CommentController::class,'updateApproved'])->name('comment.update-approved');

});

//profile
Route::prefix('profile')->name('home.')->group(function (){
    //Profile
    Route::get('/',[UserProfileController::class,'index'])->name('users_profile.index');
    //Comment
    Route::get('/comments',[UserProfileController::class,'comments'])->name('comments.users_profile.index');
    //Wishlist
    Route::get('/wishlist',[UserProfileController::class,'wishlist'])->name('wishlist.users_profile.index');
    //Address
    Route::get('/addresses',[AddressController::class,'index'])->name('addresses.user_profile.index');
    //Orders
    Route::get('/orders',[CartController::class,'ordersUser'])->name('orders.user_profile.index');
});

//about us
Route::get('/about-us',[HomeController::class,'aboutUs'])->name('home.about-us');
//contact-us
Route::get('/contact-us',[HomeController::class,'contactUs'])->name('home.contact-us');
Route::post('/contact-us-form',[HomeController::class,'contactUsForm'])->name('home.contact-us.form');
