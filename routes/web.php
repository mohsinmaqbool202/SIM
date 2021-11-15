<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\SizesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\ReturnProductsController;
use App\Http\Controllers\DashboardController;

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

Route::get('/', function () {
    return redirect('/login');
});


Route::middleware(['auth:sanctum'])->group(function(){
    #Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    #users
    Route::resource('users', UsersController::class);

    #Category
    Route::resource('categories', CategoriesController::class);
    Route::get('/api/categories', [CategoriesController::class, 'getCategoriesJson']);

    #Brand
    Route::resource('brands', BrandsController::class);
    Route::get('/api/brands', [BrandsController::class, 'getBrandsJson']);

    #Size
    Route::resource('sizes', SizesController::class);
    Route::get('/api/sizes', [SizesController::class, 'getSizesJson']);

    #Product
    Route::resource('products', ProductsController::class);
    Route::get('/api/products', [ProductsController::class,'getProductsJson']);

    #stock
    Route::get('stocks', [StocksController::class, 'stock'])->name('stock');
    Route::post('stocks', [StocksController::class, 'stockSubmit'])->name('stock');
    Route::get('stocks/history', [StocksController::class, 'history'])->name('stockHistory');

    #return products
    Route::get('return-products', [ReturnProductsController::class, 'returnProducts'])
        ->name('returnProduct');
    Route::post('return-products', [ReturnProductsController::class, 'returnProductSubmit'])
        ->name('returnProductSubmit');
    Route::get('return-products/history', [ReturnProductsController::class, 'history'])
        ->name('returnProductHistory');

});

