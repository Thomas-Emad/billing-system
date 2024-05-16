<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\InvoiceController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// User Auth
Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});

Route::apiResource("categories", CategoryController::class)->middleware('auth');
Route::apiResource("products", ProductController::class)->middleware('auth');

// Customer Controller
Route::group([
    'middleware' => 'auth',
    'prefix' => 'customers'
], function ($router) {
    Route::get('/index', [CustomerController::class, 'index']);
    Route::post('/store', [CustomerController::class, 'store']); 
    Route::put('/update/{id}', [CustomerController::class, 'update']); 
    Route::delete('/destroy/{id}', [CustomerController::class, 'destroy']); 
});

// Invoice Controller
Route::group([
    'middleware' => 'auth',
    'prefix' => 'invoices'
], function ($router) {
    Route::get('/index', [InvoiceController::class, 'index']);
    Route::post('/store', [InvoiceController::class, 'store']); 
    Route::put('/update/{id}', [InvoiceController::class, 'update']); 
    Route::delete('/destroy/{id}', [InvoiceController::class, 'destroy']); 
});
