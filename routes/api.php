<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// User Auth
Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::group(['middleware' => 'JWTAuth'], function () {

    // User Auth
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user-profile', [AuthController::class, 'userProfile']);
    });

    Route::apiResource("categories", CategoryController::class);
    Route::apiResource("products", ProductController::class);

    // Customer Controller
    Route::group(['prefix' => 'customers'], function () {
        Route::get('/index', [CustomerController::class, 'index']);
        Route::post('/store', [CustomerController::class, 'store']);
        Route::put('/update/{id}', [CustomerController::class, 'update']);
        Route::delete('/destroy/{id}', [CustomerController::class, 'destroy']);
    });

    // Invoice Controller
    Route::group(['prefix' => 'invoices'], function () {
        Route::get('/index', [InvoiceController::class, 'index']);
        Route::post('/CretaInvoice', [InvoiceController::class, 'CretaInvoice']);
        Route::post('/AddProductInInvoice', [InvoiceController::class, 'AddProductInInvoice']);
        Route::put('/update/{id}', [InvoiceController::class, 'update']);
        Route::get('/search', [InvoiceController::class, 'search']);
        Route::delete('/destroy/{invoice_id}/{product_id}', [InvoiceController::class, 'destroy']);
    });

    // Reports Controller
    Route::group(['prefix' => 'reports'], function () {
        Route::get('/products', [ReportController::class, 'products']);
        Route::get('/invoices', [ReportController::class, 'invoices']);
        Route::get('/profits', [ReportController::class, 'profits']);
        Route::get('/InvoicesUnPaid', [ReportController::class, 'InvoicesUnPaid']);
    });
});
