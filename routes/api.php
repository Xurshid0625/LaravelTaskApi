<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StorageController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\ClientRefundController;
use App\Http\Controllers\Api\PurchaseRefundController;

Route::post('/purchases', [PurchaseController::class, 'store']);
Route::get('/products/available', [ProductController::class, 'available']);
Route::post('/orders', [OrderController::class, 'store']);
Route::post('/purchase-refunds', [PurchaseRefundController::class, 'store']);
Route::post('/client-refunds', [ClientRefundController::class, 'store']);
Route::get('/batches/profit', [BatchController::class, 'profit']);
Route::get('/storages/remaining', [StorageController::class, 'remaining']);
