<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

use App\Http\Controllers\QuickbooksController;
use Illuminate\Support\Facades\Route;
use App\Services\MygrantScraperService;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('test', function () {
//     return bcrypt('password');
// });
Route::get('/pending', function () {
    return redirect(route('dashboard'));
});

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/test',function(MygrantScraperService $service) {
   $result  =  $service->loginAndFetchPrices('sabrisaadoon@gmail.com', '2121');

});




Route::get('/dashboard', [DashboardController::class,'index'])->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->controller(AdminController::class)->group(function () {
    Route::get('/admin/', 'index')->name('admin.index');
    Route::get('/admin/users/{user}/approve', 'approve')->name('admin.approve');
    Route::get('/admin/users/{user}/destroy', 'destroy')->name('admin.destroy');
    // cart routes
    Route::get('/cart/products', [CartController::class, 'getCartProducts'])->name('cart.products');

    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/update-cart', [CartController::class, 'updateCartBeforeCheckout']);
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');


});


// Route::get('/test', [QuickbooksController::class,'index'])->middleware(['auth'])->name('dashboard');


require __DIR__.'/auth.php';
