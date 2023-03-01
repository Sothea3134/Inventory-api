<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportStock\ImportStockController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\User\UsermanagementController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Reports\ReportsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
// | Here is where you can register web routes for your application. These
// | routes are loaded by the RouteServiceProvider within a group which
// | contains the "web" middleware group. Now create something great!
// |
// */

Route::group(['middleware' => 'active.nav'], function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index']);


    // Import-stock
    Route::resource('import', ImportStockController::class);
    // search create import
    Route::post('import/search', [ImportStockController::class, 'search']);
    // // search Update import
    Route::post('import/{id}/search', [ImportStockController::class, 'search']);
    // fetch data import
    Route::get('import/{id}/fetch', [ImportStockController::class, 'fetchData']);
    Route::get('fetch-import-product', [ImportStockController::class, 'fetchProduct']);

    // Product resource
    Route::resource('product', ProductController::class);

    // Customer resource
    Route::resource('customer', CustomerController::class);


    // Sales resource
    Route::resource('sales', SalesController::class);
    // search create sales
    Route::post('sales/search', [SalesController::class, 'search']);
    // search Update sales
    Route::post('sales/{id}/search', [SalesController::class, 'search']);

    // Store Customer in sale module
    Route::post('sales/customer/store', [SalesController::class, 'storeCustomer']);
    // fetch customer data
    Route::get('sales/customer/fetch', [SalesController::class, 'fetchCustomerData']);
    // fetch data sales
    Route::get('sales/{id}/fetch', [SalesController::class, 'fetchData']);
    // fetch products 
    Route::get('fetch-sale-product', [SalesController::class, 'fetchProduct']);
    // Preview Invoice
    Route::get('sales/{id}/invoice', [SalesController::class, 'previewInvoice']);

    // Reports
    Route::get('reports/product-imports', [ReportsController::class, 'reportProductImport']);
    Route::get('reports/product-sales', [ReportsController::class, 'reportProductSale']);

    // filter data
    Route::post('reports/filter', [ReportsController::class, 'reportFilter']);



    // User Management
    Route::get('user-management', [UsermanagementController::class, 'index']);
});
