<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;

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
Route::post('/admin/product/image-upload', [AdminController::class, 'imageUpload']);
Route::get('/admin/products', [AdminController::class, 'getAllProducts']);
Route::post('/products/search', [AdminController::class, 'searchProducts']);
Route::get('/product/{id}', [AdminController::class, 'getProductDetails']);
Route::get('/product-listing', [AdminController::class, 'getProductListing']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/admin/logout', [AdminController::class, 'adminLogout']);
    Route::post('/admin/create', [AdminController::class, 'createNewAdminUser']);
    Route::post('/admin/product/new', [AdminController::class, 'addProduct']);
    Route::delete('/admin/product/{id}', [AdminController::class, 'deleteProduct']);
    Route::post('/user/logout', [AdminController::class, 'logout']);
});
