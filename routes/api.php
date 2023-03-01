<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ImportStockController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\SaleController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('import_stocks', ImportStockController::class);
Route::resource('sales', SaleController::class);
Route::resource('customers', CustomerController::class);
Route::get('report', [ReportController::class, 'reportProduct']);
