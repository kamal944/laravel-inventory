<?php

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

use App\Http\Controllers\ProductKeluarController;
use App\Http\Controllers\ProductMasukController;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('lang/{locale}', [App\Http\Controllers\LanguageController::class, 'switchLang'])
    ->name('lang.switch')
    ->middleware('web');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('dashboard', function () {
   return view('layouts.master');
});



Route::group(['middleware' => 'auth'], function () {
    Route::resource('categories','CategoryController');
    Route::get('/apiCategories','CategoryController@apiCategories')->name('api.categories');
    Route::get('/exportCategoriesAll','CategoryController@exportCategoriesAll')->name('exportPDF.categoriesAll');
    Route::get('/exportCategoriesAllExcel','CategoryController@exportExcel')->name('exportExcel.categoriesAll');


    Route::resource('customers','CustomerController');
    Route::get('/apiCustomers','CustomerController@apiCustomers')->name('api.customers');
    Route::post('/importCustomers','CustomerController@ImportExcel')->name('import.customers');
    Route::get('/exportCustomersAll','CustomerController@exportCustomersAll')->name('exportPDF.customersAll');
    Route::get('/exportCustomersAllExcel','CustomerController@exportExcel')->name('exportExcel.customersAll');

    Route::resource('sales','SaleController');
    Route::get('/apiSales','SaleController@apiSales')->name('api.sales');
    Route::post('/importSales','SaleController@ImportExcel')->name('import.sales');
    Route::get('/exportSalesAll','SaleController@exportSalesAll')->name('exportPDF.salesAll');
    Route::get('/exportSalesAllExcel','SaleController@exportExcel')->name('exportExcel.salesAll');

    Route::resource('suppliers','SupplierController');
    Route::get('/apiSuppliers','SupplierController@apiSuppliers')->name('api.suppliers');
    Route::post('/importSuppliers','SupplierController@ImportExcel')->name('import.suppliers');
    Route::get('/exportSupplierssAll','SupplierController@exportSuppliersAll')->name('exportPDF.suppliersAll');
    Route::get('/exportSuppliersAllExcel','SupplierController@exportExcel')->name('exportExcel.suppliersAll');

    Route::resource('products','ProductController');
    Route::get('/apiProducts','ProductController@apiProducts')->name('api.products');

    Route::resource('productsOut','ProductKeluarController');
    Route::get('/products/export/pdf', [App\Http\Controllers\ProductController::class, 'exportProductsAll'])->name('exportPDF.productsAll');
    Route::get('/products/export/excel', [App\Http\Controllers\ProductController::class, 'exportExcel'])->name('exportExcel.productsAll');
    Route::post('/products/import', [\App\Http\Controllers\ProductController::class, 'import'])->name('products.import');
    Route::get('/apiProductsOut','ProductKeluarController@apiProductsOut')->name('api.productsOut');
    Route::get('/exportProductKeluar/{id}','ProductKeluarController@exportProductKeluar')->name('exportPDF.productKeluar');
    Route::get('/product-keluar/export/excel', [ProductKeluarController::class, 'exportExcel'])->name('exportExcel.productKeluarAll');
    Route::get('/product-keluar/export/pdf', [ProductKeluarController::class, 'exportPDF'])->name('exportPDF.productKeluarAll');
    Route::get('/product-keluar/export/invoice', [ProductKeluarController::class, 'exportPDFInvoice'])->name('export_invoice');
    Route::get('/product-keluar/{id}/export-pdf', [ProductKeluarController::class, 'exportProductKeluar'])->name('exportPDF.productKeluarSingle');

    Route::resource('productsIn','ProductMasukController');
    Route::get('/apiProductsIn','ProductMasukController@apiProductsIn')->name('api.productsIn');
    Route::get('/exportProductMasukAll', [ProductMasukController::class, 'exportProductMasukAll'])->name('exportPDF.productMasukAll');
    Route::get('/product-masuk/export/excel', [App\Http\Controllers\ProductMasukController::class, 'exportExcel'])->name('exportExcel.productMasukAll');
    Route::get('/exportProductMasuk/{id}','ProductMasukController@exportProductMasuk')->name('exportPDF.productMasuk');
    Route::get('/exportProductMasuk/export/invoice-in',[App\Http\Controllers\ProductMasukController::class, 'exportPDFInvoice_in'])->name('invoice-in');
});

