<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {  
    Route::get('/shop', [ShopController::class, 'index']);
    Route::post('/create-shop', [ShopController::class, 'store']);
    Route::put('/update-shop', [ShopController::class, 'update']);
    Route::delete('/delete-shop', [ShopController::class, 'destroy']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::get('/pembelian', [BuyController::class, 'index']);
    Route::post('/pembelian', [BuyController::class, 'store']);
    Route::get('/pembelian/{id}', [BuyController::class, 'show']);
    Route::put('/pembelian/{id}', [BuyController::class, 'update']);
    Route::delete('/pembelian/{id}', [BuyController::class, 'destroy']);
});
