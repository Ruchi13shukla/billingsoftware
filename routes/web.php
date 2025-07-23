<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route\Auth;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInvoice;
use App\Models\Sale;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\DashboardController;






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

Route::get('/', function () {
    return view('welcome');
});




Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');



Route::get('/add-product', [ProductController::class, 'create'])->name('add-product');
Route::post('/insert-product', [ProductController::class, 'store'])->name('insert-product');
Route::get('/all-products', [ProductController::class, 'index'])->name('product.index');

Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
Route::put('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
Route::get('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.delete');
Route::patch('/product/{id}/update-quantity', [ProductController::class, 'updateQuantity'])->name('product.updateQuantity');




Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');
Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');

Route::get('/sale/invoice/{id}', [SaleController::class, 'showInvoice'])->name('sale.invoice');


Route::get('/sale/invoice/{id}/pdf', [SaleController::class, 'downloadPdf'])->name('sale.invoice.pdf');

Route::get('/generate-invoice', [InvoiceController::class, 'generateInvoice']);

Route::get('/generate-invoice/{saleId}', [InvoiceController::class, 'generateAndSend']);


Route::get('/sales-reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/sales-reports', [ReportController::class, 'monthlySalesReport'])->name('sales.reports');
Route::get('/product-wise-sales', [SalesReportController::class, 'productWiseReport'])->name('sales.productWise');

Route::get('/monthly-sales-report', [SaleController::class, 'monthlyProductSales'])->name('sales.monthly_report');

Route::get('/report/monthly-sales', [ReportController::class, 'monthlySalesReport'])->name('reports.monthly');
Route::get('/report/daily-sales', [ReportController::class, 'dailySalesReport'])->name('reports.daily');
Route::get('/reports/monthly-pdf', [ReportController::class, 'monthlyPdf'])->name('reports.monthly_pdf');




Route::get('/stock-report', [ReportController::class, 'stockReport'])->name('stock.report');


Route::get('/reports/profit', [ReportController::class, 'profitReport'])->name('reports.profit');


Route::get('/dashboard/stats', [DashboardController::class, 'index'])->name('dashboard.stats');



// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
