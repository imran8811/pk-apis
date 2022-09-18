<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/admin/login', [AdminController::class, 'loginAdmin']);

Route::post('/admin/product/image-upload', [ProductController::class, 'imageUpload']);
Route::get('/products', [ProductController::class, 'getAllProducts']);
Route::post('/products/search', [ProductController::class, 'searchProducts']);
Route::get('/product/{id}', [ProductController::class, 'getProductDetails']);
Route::get('/product-listing', [ProductController::class, 'getProductListing']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/admin/logout', [AdminController::class, 'adminLogout']);
    Route::post('/admin/create', [AdminController::class, 'createNewAdminUser']);
    Route::post('/user/logout', [AdminController::class, 'logout']);

    Route::post('/admin/product/new', [ProductController::class, 'addProduct']);
    Route::delete('/admin/product/{id}', [ProductController::class, 'deleteProduct']);
    Route::put('/admin/product/{id}', [ProductController::class, 'updateProduct']);
});
