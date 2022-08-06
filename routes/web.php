<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuickbooksController;
use Illuminate\Support\Facades\Route;

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

Route::get('/pending', function () {
    return redirect(route('dashboard'));
});

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class,'index'])->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->controller(AdminController::class)->group(function () {
    Route::get('/admin/', 'index')->name('admin.index');
    Route::get('/admin/users/{user}/approve', 'approve')->name('admin.approve');
    Route::get('/admin/users/{user}/destroy', 'destroy')->name('admin.destroy');

});


Route::get('/test', [QuickbooksController::class,'index'])->middleware(['auth'])->name('dashboard');


require __DIR__.'/auth.php';
